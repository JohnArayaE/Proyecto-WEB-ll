<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Vehicle;

class MaintenanceSeeder extends Seeder
{
    public function run(): void
    {
        $vehicles = Vehicle::all();

        $types = ['Preventivo', 'Correctivo', 'Urgente'];
        $descriptions = [
            'Cambio de aceite',
            'Revisión de frenos',
            'Alineación y balanceo',
            'Cambio de llantas',
            'Revisión del motor',
            'Sistema eléctrico',
            'Mantenimiento general',
            'Revisión de suspensión'
        ];

        $maintenances = [];

        foreach ($vehicles as $vehicle) {

            $count = rand(2, 5);

            for ($i = 0; $i < $count; $i++) {

                $statusOptions = ['in_progress', 'completed', 'cancelled'];
                $status = $statusOptions[array_rand($statusOptions)];

                $start = now()->subDays(rand(1, 30));
                $end = $status === 'completed'
                    ? (clone $start)->addDays(rand(1, 5))
                    : null;

                $maintenances[] = [
                    'vehicle_id' => $vehicle->id,
                    'type' => $types[array_rand($types)],
                    'start_date' => $start,
                    'end_date' => $end,
                    'description' => $descriptions[array_rand($descriptions)],
                    'cost' => rand(50, 500),
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Ajustar estado del vehículo
            $lastStatus = $status;

            DB::table('vehicles')
                ->where('id', $vehicle->id)
                ->update([
                    'status' => $lastStatus === 'in_progress'
                        ? 'under maintenance' // 👈 IMPORTANTE (con espacio)
                        : 'available',
                    'updated_at' => now()
                ]);
        }

        DB::table('maintenances')->insert($maintenances);
    }
}