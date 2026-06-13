<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
        ]);

        Category::create([
            'name' => 'Apparel',
            'description' => 'Clothing and accessories',
        ]);

        Category::create([
            'name' => 'Home Goods',
            'description' => 'Furniture and home decor',
        ]);

        Category::factory()->count(5)->create();
        
        Supplier::create([
            'company_name' => 'Supplier A',
            'contact_title' => 'Manager',
            'contact_name' => 'John Doe',
            'contact_email' => 'supplierA@example.com',
            'contact_number' => '0123456789',
            'address' => '123, Main Street',
            'city' => 'George Town',
            'postcode' => '10000',
            'state' => 'Penang',
            'country' => 'Malaysia',
        ]);

        Supplier::create([
            'company_name' => 'Supplier B',
            'contact_title' => 'Manager',
            'contact_name' => 'Jane Doe',
            'contact_email' => 'supplierB@example.com',
            'contact_number' => '0123456790',
            'address' => '124, Main Street',
            'city' => 'George Town',
            'postcode' => '10000',
            'state' => 'Penang',
            'country' => 'Malaysia',
        ]);

        Supplier::create([
            'company_name' => 'Supplier C',
            'contact_title' => 'Manager',
            'contact_name' => 'Jim Doe',
            'contact_email' => 'supplierC@example.com',
            'contact_number' => '0123456791',
            'address' => '125, Main Street',
            'city' => 'George Town',
            'postcode' => '10000',
            'state' => 'Penang',
            'country' => 'Malaysia',
        ]);

        Supplier::factory()->count(5)->create();

        $laptop = Product::create([
            'name' => 'Laptop',
            'description' => 'A high-performance laptop for work and play.',
            'price' => 999.99,
            'quantity' => 50,
            'discount' => 10.00,
            'rating' => 4.5,
            'review' => 1000,
            'category_id' => Category::inRandomOrder()->first()->uuid,
        ]);

        $laptop->suppliers()->attach(Supplier::inRandomOrder()->first()->uuid);

        $shirt = Product::create([
            'name' => 'Shirt',
            'description' => 'A stylish shirt for any occasion.',
            'price' => 29.99,
            'quantity' => 200,
            'discount' => 5.00,
            'rating' => 4.0,
            'review' => 500,
            'category_id' => Category::inRandomOrder()->first()->uuid,
        ]);

        $shirt->suppliers()->attach(Supplier::inRandomOrder()->first()->uuid);

        Product::factory()->count(100)->create();
    }
}
