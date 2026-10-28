<?php

namespace Tests\Feature;

use App\Jobs\SendOrderConfirmation;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_can_be_created_and_stock_is_deducted(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'name' => 'Test USB Cable',
            'product_code' => 'USB-TEST-001',
            'unit_price' => 100.00,
            'tax_percentage' => 18.00,
            'stock_quantity' => 10,
        ]);

        $response = $this->postJson('/api/v1/orders', [
            'customer' => [
                'name' => 'Test Customer',
                'email' => 'test@example.com',
            ],
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertCreated();

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 1);

        $this->assertDatabaseHas('orders', [
            'customer_id' => $response->json('data.customer_id'),
            'subtotal' => '200.00',
            'tax_total' => '36.00',
            'grand_total' => '236.00',
        ]);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 8,
        ]);

        Queue::assertPushed(
            SendOrderConfirmation::class,
            fn (SendOrderConfirmation $job) =>
                $job->orderId === $response->json('data.id')
        );
    }

    public function test_order_is_rejected_when_stock_is_insufficient(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'name' => 'Limited Stock Product',
            'product_code' => 'LIMITED-TEST-001',
            'unit_price' => 100.00,
            'tax_percentage' => 18.00,
            'stock_quantity' => 1,
        ]);

        $response = $this->postJson('/api/v1/orders', [
            'customer' => [
                'name' => 'Test Customer',
                'email' => 'stock-test@example.com',
            ],
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                ],
            ],
        ]);

        $response->assertUnprocessable();

        $this->assertDatabaseCount('orders', 0);
        $this->assertDatabaseCount('order_items', 0);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 1,
        ]);

        Queue::assertNothingPushed();
    }

    public function test_order_rejects_a_quantity_of_zero(): void
    {
        Queue::fake();

        $product = Product::factory()->create([
            'product_code' => 'ZERO-TEST-001',
            'unit_price' => 100.00,
            'tax_percentage' => 18.00,
            'stock_quantity' => 10,
        ]);

        $response = $this->postJson('/api/v1/orders', [
            'customer' => [
                'name' => 'Test Customer',
                'email' => 'quantity-test@example.com',
            ],
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 0,
                ],
            ],
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['items.0.quantity']);

        $this->assertDatabaseCount('orders', 0);

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'stock_quantity' => 10,
        ]);

        Queue::assertNothingPushed();
    }
}