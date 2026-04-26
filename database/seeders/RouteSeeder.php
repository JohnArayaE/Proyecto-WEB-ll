<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RouteSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('routes')->insert([
            [
                'name' => 'San José - Alajuela',
                'origin' => 'San José',
                'destination' => 'Alajuela',
                'distance' => 20.5,
                'description' => 'Ruta principal entre San José y Alajuela',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alajuela - Heredia',
                'origin' => 'Alajuela',
                'destination' => 'Heredia',
                'distance' => 15.3,
                'description' => 'Conexión corta entre provincias',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Heredia - Cartago',
                'origin' => 'Heredia',
                'destination' => 'Cartago',
                'distance' => 35.7,
                'description' => 'Ruta larga atravesando San José',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'San José - Cartago',
                'origin' => 'San José',
                'destination' => 'Cartago',
                'distance' => 25.0,
                'description' => 'Ruta común hacia Cartago',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alajuela - Puntarenas',
                'origin' => 'Alajuela',
                'destination' => 'Puntarenas',
                'distance' => 100.2,
                'description' => 'Ruta larga hacia la costa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}