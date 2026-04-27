<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('vehicles')->insert([
            [
                'plate' => 'ABC123',
                'brand' => 'Toyota',
                'model' => 'Hilux',
                'year' => 2020,
                'type' => 'Pickup',
                'capacity' => 5,
                'fuel_type' => 'Diesel',
                'image' => null,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate' => 'DEF456',
                'brand' => 'Hyundai',
                'model' => 'Accent',
                'year' => 2019,
                'type' => 'Sedan',
                'capacity' => 5,
                'fuel_type' => 'Gasoline',
                'image' => null,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate' => 'GHI789',
                'brand' => 'Isuzu',
                'model' => 'NPR',
                'year' => 2018,
                'type' => 'Truck',
                'capacity' => 3,
                'fuel_type' => 'Diesel',
                'image' => null,
                'status' => 'under maintenance',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate' => 'JKL321',
                'brand' => 'Nissan',
                'model' => 'Frontier',
                'year' => 2021,
                'type' => 'Pickup',
                'capacity' => 5,
                'fuel_type' => 'Diesel',
                'image' => null,
                'status' => 'available',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'plate' => 'MNO654',
                'brand' => 'Kia',
                'model' => 'Sportage',
                'year' => 2022,
                'type' => 'SUV',
                'capacity' => 5,
                'fuel_type' => 'Gasoline',
                'image' => null,
                'status' => 'unavailable',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}