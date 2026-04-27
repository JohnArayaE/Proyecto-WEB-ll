<?php

namespace Database\Seeders;

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
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'telephone' => '8888-8888',
            'password' => Hash::make('password123'),
            'role_id' => 1 // admin
        ]);

        User::create([
            'name' => 'Driver User',
            'email' => 'driver@example.com',
            'telephone' => '7777-7777',
            'password' => Hash::make('password123'),
            'role_id' => 3 // driver
        ]);

        User::create([
            'name' => 'Operator User',
            'email' => 'operator@example.com',
            'telephone' => '6666-6666',
            'password' => Hash::make('password123'),
            'role_id' => 2 // operator
        ]);
    }
}