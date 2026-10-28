<?php

namespace App\Services;

use App\Jobs\SendOrderConfirmation;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderService
{
    public function create(array $data): Order
    {
        $order = DB::transaction(function () use ($data) {
            $email = strtolower(trim($data['customer']['email']));

            $customer = Customer::firstOrCreate(
                ['email' => $email],
                ['name' => trim($data['customer']['name'])]
            );

            $requestedItems = collect($data['items'])->keyBy('product_id');

            $productIds = $requestedItems->keys()
                ->map(fn ($id) => (int) $id)
                ->sort()
                ->values();

            $products = Product::query()
                ->whereIn('id', $productIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $subtotalCents = 0;
            $taxTotalCents = 0;
            $lines = [];

            foreach ($requestedItems as $productId => $item) {
                $product = $products->get((int) $productId);
                $quantity = (int) $item['quantity'];

                if (!$product || $product->stock_quantity < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => [
                            'Insufficient stock or product unavailable.',
                        ],
                    ]);
                }

                $unitPriceCents = (int) round(
                    (float) $product->unit_price * 100
                );

                $lineSubtotalCents = $unitPriceCents * $quantity;

                $lineTaxCents = (int) round(
                    $lineSubtotalCents
                    * (float) $product->tax_percentage / 100
                );

                $subtotalCents += $lineSubtotalCents;
                $taxTotalCents += $lineTaxCents;

                $lines[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPriceCents / 100,
                    'tax_percentage' => $product->tax_percentage,
                    'line_subtotal' => $lineSubtotalCents / 100,
                    'tax_amount' => $lineTaxCents / 100,
                    'line_total' => ($lineSubtotalCents + $lineTaxCents) / 100,
                ];
            }

            $order = Order::create([
                'customer_id' => $customer->id,
                'subtotal' => $subtotalCents / 100,
                'tax_total' => $taxTotalCents / 100,
                'grand_total' => ($subtotalCents + $taxTotalCents) / 100,
                'status' => 'confirmed',
            ]);

            foreach ($lines as $line) {
                $order->items()->create([
                    'product_id' => $line['product']->id,
                    'quantity' => $line['quantity'],
                    'unit_price' => $line['unit_price'],
                    'tax_percentage' => $line['tax_percentage'],
                    'line_subtotal' => $line['line_subtotal'],
                    'tax_amount' => $line['tax_amount'],
                    'line_total' => $line['line_total'],
                ]);

                $line['product']->decrement(
                    'stock_quantity',
                    $line['quantity']
                );
            }

            return $order;
        }, 3);

        SendOrderConfirmation::dispatch($order->id)->afterCommit();

        return $order->load(['customer', 'items.product']);
    }
}
