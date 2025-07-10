<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Addresses;
use App\Models\Category;
use App\Models\Product;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create test users
        $customer = User::factory()->create([
            'name' => 'Customer Test',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer'
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin Test',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create sample address for customer
        Addresses::create([
            'user_id' => $customer->id,
            'label' => 'Rumah',
            'recipient_name' => 'Customer Test',
            'phone_number' => '081234567890',
            'address_line' => 'Jl. Contoh No. 123',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'postal_code' => '12345',
            'is_default' => true
        ]);

        // Create sample categories if they don't exist
        if (Category::count() == 0) {
            Category::create([
                'name' => 'Elektronik',
                'slug' => 'elektronik',
                'description' => 'Produk elektronik dan gadget'
            ]);

            Category::create([
                'name' => 'Fashion',
                'slug' => 'fashion',
                'description' => 'Pakaian dan aksesoris'
            ]);
        }
    }
}
