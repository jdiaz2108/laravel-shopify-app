<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shopify_id' => 'gid://shopify/Product/' . $this->faker->unique()->numerify('######'),
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'vendor' => $this->faker->company(),
            'product_type' => $this->faker->word(),
            'handle' => $this->faker->slug(),
            'status' => 'active',
            'featured_image_url' => $this->faker->imageUrl(),
            'synced_at' => now(),
        ];
    }
}
