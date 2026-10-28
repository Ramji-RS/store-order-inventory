<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductCustomerSeeder extends Seeder
{
    public function run(): void
    {
        Product::factory()->createMany([
            [
                'name' => 'USB Cable',
                'product_code' => 'USB-001',
                'unit_price' => 150.00,
                'tax_percentage' => 18,
                'stock_quantity' => 20,
            ],
            [
                'name' => 'Wireless Mouse',
                'product_code' => 'MOU-001',
                'unit_price' => 500.00,
                'tax_percentage' => 18,
                'stock_quantity' => 10,
            ],
            [
                'name' => 'Keyboard',
                'product_code' => 'KEY-001',
                'unit_price' => 800.00,
                'tax_percentage' => 18,
                'stock_quantity' => 4,
            ],
        ]);

        Product::factory()->count(10)->create();

        Customer::factory()->createMany([
            ['name' => 'Arun Kumar', 'email' => 'arun@example.com'],
            ['name' => 'Priya Devi', 'email' => 'priya@example.com'],
        ]);

        Customer::factory()->count(5)->create();
    }
}