<?php

namespace Tests\Feature;

use App\Models\Supplier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SupplierApiTest extends TestCase
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
     * Test the supplier creation API endpoint.
     */
    public function test_supplier_creation()
    {
        $payload = [
            'company_name' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'contact_title' => $this->faker->jobTitle(),
            'contact_email' => $this->faker->email(),
            'contact_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postcode' => $this->faker->postcode(),
            'country' => $this->faker->country(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/suppliers', $payload);

        $response->assertStatus(201)
            ->assertJsonFragment([
                'company_name' => $payload['company_name'],
                'contact_name' => $payload['contact_name'],
                'contact_title' => $payload['contact_title'],
                'contact_email' => $payload['contact_email'],
                'contact_number' => $payload['contact_number'],
                'address' => $payload['address'],
                'city' => $payload['city'],
                'state' => $payload['state'],
                'postcode' => $payload['postcode'],
                'country' => $payload['country'],
            ]);
    }

    /**
     * Test the supplier listing API endpoint.
     */
    public function test_supplier_listing()
    {
        $suppliers = Supplier::factory()->count(3)->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson('/api/suppliers');

        $response->assertStatus(200)
            ->assertJsonCount($suppliers->count(), 'data');
    }

    /**
     * Test the supplier retrieval API endpoint.
     */
    public function test_supplier_retrieval()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->getJson("/api/suppliers/{$supplier->uuid}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Supplier retrieved successfully.');
    }

    /**
     * Test the supplier update API endpoint.
     */
    public function test_supplier_update()
    {
        $supplier = Supplier::factory()->create();

        $payload = [
            'company_name' => $this->faker->company(),
            'contact_name' => $this->faker->name(),
            'contact_title' => $this->faker->jobTitle(),
            'contact_email' => $this->faker->email(),
            'contact_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postcode' => $this->faker->postcode(),
            'country' => $this->faker->country(),
        ];

        $response = $this->actingAs($this->user, 'sanctum')->putJson("/api/suppliers/{$supplier->uuid}", $payload);

        $response->assertStatus(200);
    }

    /**
     * Test the supplier deletion API endpoint.
     */
    public function test_supplier_deletion()
    {
        $supplier = Supplier::factory()->create();

        $response = $this->actingAs($this->user, 'sanctum')->delete("/api/suppliers/{$supplier->uuid}");

        $response->assertStatus(200)
            ->assertJsonPath('message', 'Supplier deleted successfully.');
    }

    /**
     * Test the supplier validation API endpoint.
     */
    public function test_supplier_validation()
    {
        $payload = [
            'company_name' => '',
            'contact_name' => '',
            'contact_title' => '',
            'contact_email' => '',
            'contact_number' => '',
            'address' => '',
            'city' => '',
            'state' => '',
            'postcode' => '',
            'country' => '',
        ];

        $response = $this->actingAs($this->user, 'sanctum')->postJson('/api/suppliers', $payload);

        $response->assertStatus(422);
    }
}
