<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Data 1: Operator
        User::create([
            'name' => 'Operator',
            'email' => 'operator@trusur.com',
            'role' => 'Operator',
            'password' => Hash::make('password123'), // Password default
            'created_at' => now()->subDay(), // Biar kelihatan 'a day ago'
            'updated_at' => now()->subDay(),
        ]);

        // Data 2: Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@trusur.com',
            'role' => 'Administrator',
            'password' => Hash::make('password123'),
            'created_at' => now()->subDay(),
            'updated_at' => now()->subDay(),
        ]);
    }
}
