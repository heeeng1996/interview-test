<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'company_name' => $this->faker->company(),
            'contact_title' => $this->faker->jobTitle(),
            'contact_name' => $this->faker->name(),
            'contact_email' => $this->faker->unique()->safeEmail(),
            'contact_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city' => $this->faker->city(),
            'postcode' => $this->faker->postcode(),
            'state' => $this->faker->state(),
            'country' => $this->faker->country(),
        ];
    }
}
