<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user if not exists
        if (!User::where('email', 'admin@ankulaa.com')->exists()) {
            User::create([
                'name' => 'Admin Ankulaa',
                'email' => 'admin@ankulaa.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]);
        }

        // Create some sample customers
        $customers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Bob Johnson',
                'email' => 'bob@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Alice Williams',
                'email' => 'alice@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
            [
                'name' => 'Charlie Brown',
                'email' => 'charlie@example.com',
                'password' => Hash::make('password'),
                'role' => 'customer',
            ],
        ];

        foreach ($customers as $customer) {
            if (!User::where('email', $customer['email'])->exists()) {
                User::create($customer);
            }
        }
    }
}
