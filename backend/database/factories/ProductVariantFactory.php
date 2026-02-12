<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'shopify_id' => 'gid://shopify/ProductVariant/' . $this->faker->unique()->numerify('######'),
            'title' => $this->faker->words(2, true),
            'sku' => $this->faker->bothify('SKU-??-###'),
            'price' => $this->faker->randomFloat(2, 1, 999),
            'inventory_quantity' => $this->faker->numberBetween(0, 100),
            'synced_at' => now(),
        ];
    }
}
