<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Vehicle;

class RequestSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $vehicles = Vehicle::all();

        $requests = [];

        $observations = [
            'Solicitud para viaje de trabajo',
            'Uso del vehículo para diligencias',
            'Requerido para transporte de equipo',
            'Viaje urgente',
            'Solicitud normal'
        ];

        foreach ($users as $user) {

            // cada usuario hace entre 2 y 4 solicitudes
            $count = rand(2, 4);

            for ($i = 0; $i < $count; $i++) {

                $vehicle = $vehicles->random();

                $statusOptions = ['approved', 'rejected', 'pending'];
                $status = $statusOptions[array_rand($statusOptions)];

                $start = now()->addDays(rand(1, 10));
                $end = (clone $start)->addHours(rand(2, 8));

                // solo admin aprueba (role_id = 1)
                $approvedBy = $status === 'approved'
                    ? User::where('role_id', 1)->inRandomOrder()->first()?->id
                    : null;

                $requests[] = [
                    'user_id' => $user->id,
                    'vehicle_id' => $vehicle->id,
                    'start_date' => $start,
                    'end_date' => $end,
                    'status' => $status,
                    'observations' => $observations[array_rand($observations)],
                    'approved_by' => $approvedBy,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // lógica opcional: si se aprueba, el vehículo queda unavailable
                if ($status === 'approved') {
                    DB::table('vehicles')
                        ->where('id', $vehicle->id)
                        ->update([
                            'status' => 'unavailable',
                            'updated_at' => now()
                        ]);
                }
            }
        }

        DB::table('requests')->insert($requests);
    }
}