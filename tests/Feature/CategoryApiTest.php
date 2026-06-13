<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryApiTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);
    }

    /**
     * Test the category creation API endpoint.
     */
    public function test_category_creation()
    {
        $payload = [
            'name' => $this->faker->word(),
            'description' => $this->faker->word(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/categories', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => $payload['name'],
                'description' => $payload['description'],
            ]);
    }

    /**
     * Test the category listing API endpoint.
     */
    public function test_category_listing()
    {
        $categories = Category::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/categories');

        $response->assertStatus(200);
    }

    /**
     * Test the category retrieval API endpoint.
     */
    public function test_category_retrieval()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/categories/{$category->uuid}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Category retrieved successfully.');
    }

    /**
     * Test the category update API endpoint.
     */
    public function test_category_update()
    {
        $category = Category::factory()->create();

        $payload = [
            'name' => $this->faker->word,
            'description' => $this->faker->word,
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/categories/{$category->uuid}", $payload);

        $response->assertStatus(200);
    }

    /**
     * Test the category deletion API endpoint.
     */
    public function test_category_deletion()
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->delete("/api/categories/{$category->uuid}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Category deleted successfully.');
    }

    /**
     * Test the category validation API endpoint.
     */
    public function test_category_validation()
    {
        $payload = [
            'name' => '',
            'description' => '',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/categories', $payload);

        $response->assertStatus(422);
    }
}
