<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);

        $this->category = Category::factory()->create();
    }

    /**
     * Test the product creation API endpoint.
     */
    public function test_product_creation()
    {
        $payload = [
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'quantity' => $this->faker->numberBetween(10, 100),
            'discount' => $this->faker->randomFloat(2, 0, 10),
            'rating' => $this->faker->randomFloat(1, 1, 5),
            'review' => $this->faker->numberBetween(1, 50),
            'category_id' => $this->category->uuid,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/products', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'description' => $payload['description'],
                'price' => $payload['price'],
                'category_id' => $payload['category_id'],
            ]);
    }

    /**
     * Test the product listing API endpoint.
     */
    public function test_product_listing()
    {
        $products = Product::factory()->count(3)->create([
            'category_id' => $this->category->uuid,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/products');

        $response->assertStatus(200);
    }

    /**
     * Test the product retrieval API endpoint.
     */
    public function test_product_retrieval()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->uuid,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/products/{$product->uuid}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Product retrieved successfully.');
    }

    /**
     * Test the product update API endpoint.
     */
    public function test_product_update()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->uuid,
        ]);

        $payload = [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'quantity' => $this->faker->numberBetween(10, 100),
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/products/{$product->uuid}", $payload);

        $response->assertStatus(200)
            ->assertJson(['message' => 'Product updated successfully.']);
    }

    /**
     * Test the product deletion API endpoint.
     */
    public function test_product_deletion()
    {
        $product = Product::factory()->create([
            'category_id' => $this->category->uuid,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->delete("/api/products/{$product->uuid}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Product deleted successfully.');
    }

    /**
     * Test the product filtering API endpoint.
     */
    public function test_product_filtering()
    {
        $category2 = Category::factory()->create();

        Product::factory()->create([
            'category_id' => $this->category->uuid,
        ]);

        Product::factory()->create([
            'category_id' => $category2->uuid,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/products?category_id={$this->category->uuid}");

        $response->assertStatus(200);
    }

    /**
     * Test the product pagination API endpoint.
     */
    public function test_product_pagination()
    {
        Product::factory()->count(15)->create([
            'category_id' => $this->category->uuid,
        ]);

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/products?page=1&per_page=10');

        $response->assertStatus(200);
    }

    /**
     * Test the product validation API endpoint.
     */
    public function test_product_validation()
    {
        $payload = [
            'name' => '',
            'description' => '',
            'price' => -10,
            'category_id' => null,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/products', $payload);

        $response->assertStatus(422);
    }
}
