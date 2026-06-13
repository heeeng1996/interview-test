<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
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
            'category_id' => Category::inRandomOrder()->first()?->uuid ?? Category::factory()->create()->uuid,
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'quantity' => $this->faker->numberBetween(10, 100),
            'discount' => $this->faker->randomFloat(2, 0, 10),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'review' => $this->faker->numberBetween(1, 50),
            'image' => $this->faker->imageUrl(640, 480, 'animals', true),
        ];
    }

    /**
     * Configure the factory to attach a supplier to the product.
     */
    public function configure()
    {
        return $this->afterCreating(function (Product $product) {
            $product->suppliers()->attach(
                Supplier::inRandomOrder()->first()?->uuid ?? Supplier::factory()->create()->uuid,
                ['uuid' => Str::uuid()->toString(),]
            );
        });
    }
}
