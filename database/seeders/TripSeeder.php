<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Request;
use App\Models\Route;

class TripSeeder extends Seeder
{
    public function run(): void
    {
        $requests = Request::where('status', 'approved')->get();
        $routes = Route::all();

        $trips = [];

        $observations = [
            'Viaje generado desde solicitud aprobada',
            'Salida sin inconvenientes',
            'Ruta completada correctamente',
            'Viaje programado',
        ];

        foreach ($requests as $request) {

            $route = $routes->random();

            $statusOptions = ['in_progress', 'completed', 'cancelled'];
            $status = $statusOptions[array_rand($statusOptions)];

            $departure = $request->start_date;
            $return = $status === 'completed'
                ? (clone $departure)->addHours(rand(2, 8))
                : null;

            $kmStart = rand(10000, 50000);
            $kmEnd = $status === 'completed'
                ? $kmStart + rand(10, 300)
                : null;

            $trips[] = [
                'user_id' => $request->user_id,
                'vehicle_id' => $request->vehicle_id,
                'route_id' => $route->id,
                'departure_time' => $departure,
                'return_time' => $return,
                'km_departure' => $kmStart,
                'km_return' => $kmEnd,
                'observations' => $observations[array_rand($observations)],
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // actualizar estado del vehículo
            DB::table('vehicles')
                ->where('id', $request->vehicle_id)
                ->update([
                    'status' => $status === 'in_progress'
                        ? 'unavailable'
                        : 'available',
                    'updated_at' => now()
                ]);
        }

        DB::table('trips')->insert($trips);
    }
}