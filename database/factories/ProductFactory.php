<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),
            'product_code' => fake()->unique()->bothify('PRD-####'),
            'unit_price' => fake()->randomFloat(2, 10, 1000),
            'tax_percentage' => fake()->randomElement([0, 5, 12, 18, 28]),
            'stock_quantity' => fake()->numberBetween(0, 100),
        ];
    }
}
