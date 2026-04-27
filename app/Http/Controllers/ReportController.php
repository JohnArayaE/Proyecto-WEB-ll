<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReportAvailableVehiclesRequest;
use App\Http\Requests\ReportFleetUsageRequest;
use App\Models\User;
use App\Models\Vehicle;
class ReportController extends Controller
{



public function availableVehicles(ReportAvailableVehiclesRequest $request)
{
    $start = $request->start_date;
    $end = $request->end_date;

    $query = Vehicle::query()

        ->leftJoin('assignments', function ($join) use ($start, $end) {
            $join->on('vehicles.id', '=', 'assignments.vehicle_id')
                 ->whereNull('assignments.deleted_at')
                 ->where('assignments.status', '!=', 'cancelled')
                 ->where(function ($q) use ($start, $end) {
                     $q->whereBetween('assignments.start_date', [$start, $end])
                       ->orWhereBetween('assignments.end_date', [$start, $end]);
                 });
        })

        ->leftJoin('maintenances', function ($join) use ($start, $end) {
            $join->on('vehicles.id', '=', 'maintenances.vehicle_id')
                 ->whereNull('maintenances.deleted_at')
                 ->where('maintenances.status', 'in_progress')
                 ->where(function ($q) use ($start, $end) {
                     $q->whereBetween('maintenances.start_date', [$start, $end])
                       ->orWhereBetween('maintenances.end_date', [$start, $end]);
                 });
        })

        ->leftJoin('requests', function ($join) use ($start, $end) {
            $join->on('vehicles.id', '=', 'requests.vehicle_id')
                 ->whereNull('requests.deleted_at')
                 ->where('requests.status', 'approved')
                 ->where(function ($q) use ($start, $end) {
                     $q->whereBetween('requests.start_date', [$start, $end])
                       ->orWhereBetween('requests.end_date', [$start, $end]);
                 });
        })

        ->whereNull('assignments.id')
        ->whereNull('maintenances.id')
        ->whereNull('requests.id')

        ->whereNull('vehicles.deleted_at')

        ->select([
            'vehicles.id',
            'vehicles.plate',
            'vehicles.brand',
            'vehicles.model',
            'vehicles.year',
            'vehicles.image',
        ]);

    $vehicles = $query
        ->orderByDesc('vehicles.id')
        ->get();

    return response()->json([
        'message' => 'Vehículos disponibles en el rango seleccionado',
        'data' => $vehicles
    ], 200);
}
    public function fleetUsage(ReportFleetUsageRequest $request)
{
    $start = $request->start_date;
    $end = $request->end_date;

    $query = Vehicle::query()

        ->join('trips', function ($join) use ($start, $end) {
            $join->on('vehicles.id', '=', 'trips.vehicle_id')
                 ->whereNull('trips.deleted_at')
                 ->where('trips.status', 'completed')
                 ->where(function ($q) use ($start, $end) {
                     $q->whereBetween('trips.departure_time', [$start, $end])
                       ->orWhereBetween('trips.return_time', [$start, $end]);
                 });
        })

        ->whereNull('vehicles.deleted_at')

        ->select([
            'vehicles.id',
            'vehicles.plate',
            DB::raw('COUNT(trips.id) as total_trips'),
            DB::raw('COALESCE(SUM(trips.km_return - trips.km_departure), 0) as total_km'),
        ])

        ->groupBy('vehicles.id', 'vehicles.plate');

    $report = $query
        ->orderByDesc('total_trips')
        ->get();

    return response()->json([
        'message' => 'Uso de flotilla por periodo',
        'data' => $report
    ], 200);
}

public function driverHistory(User $user)
{
    $query = User::query()

        ->leftJoin('trips', function ($join) {
            $join->on('users.id', '=', 'trips.user_id')
                 ->whereNull('trips.deleted_at');
        })

        ->leftJoin('vehicles', 'vehicles.id', '=', 'trips.vehicle_id')

        ->leftJoin('requests', function ($join) {
            $join->on('users.id', '=', 'requests.user_id')
                 ->whereNull('requests.deleted_at');
        })

        ->leftJoin('vehicles as request_vehicle', 'request_vehicle.id', '=', 'requests.vehicle_id')

        ->where('users.id', $user->id)

        ->select([
            'users.id as driver_id',
            'users.name as driver_name',

            // Trips
            'trips.id as trip_id',
            'trips.status as trip_status',
            'trips.departure_time',
            'trips.return_time',
            'vehicles.plate as trip_vehicle',

            // Requests
            'requests.id as request_id',
            'requests.status as request_status',
            'requests.start_date',
            'requests.end_date',
            'request_vehicle.plate as request_vehicle',
        ]);

    $history = $query
        ->orderByDesc('trips.id')
        ->get();

    return response()->json([
        'message' => 'Historial del chofer',
        'data' => $history
    ], 200);
    }
}