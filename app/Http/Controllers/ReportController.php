<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReportAvailableVehiclesRequest;
use App\Http\Requests\ReportFleetUsageRequest;
use App\Models\User;
class ReportController extends Controller
{
    /**
     * Reporte 1: Disponibilidad de vehículos por rango de fecha
     */
    public function availableVehicles(ReportAvailableVehiclesRequest $request)
    {
        $start = $request->start_date;
        $end = $request->end_date;

        $vehicles = DB::table('vehicles as v')

            // 🔹 ASIGNACIONES
            ->leftJoin('assignments as a', function ($join) use ($start, $end) {
                $join->on('v.id', '=', 'a.vehicle_id')
                     ->whereNull('a.deleted_at')
                     ->where('a.status', '!=', 'cancelled')
                     ->where(function ($q) use ($start, $end) {
                         $q->whereBetween('a.start_date', [$start, $end])
                           ->orWhereBetween('a.end_date', [$start, $end]);
                     });
            })

            // 🔹 MANTENIMIENTOS
            ->leftJoin('maintenances as m', function ($join) use ($start, $end) {
                $join->on('v.id', '=', 'm.vehicle_id')
                     ->whereNull('m.deleted_at')
                     ->where('m.status', 'in_progress')
                     ->where(function ($q) use ($start, $end) {
                         $q->whereBetween('m.start_date', [$start, $end])
                           ->orWhereBetween('m.end_date', [$start, $end]);
                     });
            })

            // 🔹 REQUESTS
            ->leftJoin('requests as r', function ($join) use ($start, $end) {
                $join->on('v.id', '=', 'r.vehicle_id')
                     ->whereNull('r.deleted_at')
                     ->where('r.status', 'approved')
                     ->where(function ($q) use ($start, $end) {
                         $q->whereBetween('r.start_date', [$start, $end])
                           ->orWhereBetween('r.end_date', [$start, $end]);
                     });
            })

            // 🔥 FILTRO FINAL (disponibles)
            ->whereNull('a.id')
            ->whereNull('m.id')
            ->whereNull('r.id')

            // 🔥 ESPECIFICACIONES COMPLETAS
            ->select(
                'v.id',
                'v.plate',
                'v.brand',
                'v.model',
                'v.year',
                'v.image'
            )

            ->orderByDesc('v.id')
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

    $report = DB::table('vehicles as v')
        ->join('trips as t', function ($join) use ($start, $end) {
            $join->on('v.id', '=', 't.vehicle_id')
                 ->whereNull('t.deleted_at')
                 ->where('t.status', 'completed')
                 ->where(function ($q) use ($start, $end) {
                     $q->whereBetween('t.departure_time', [$start, $end])
                       ->orWhereBetween('t.return_time', [$start, $end]);
                 });
        })
        ->select(
            'v.id',
            'v.plate',
            DB::raw('COUNT(t.id) as total_trips'),
            DB::raw('COALESCE(SUM(t.km_return - t.km_departure), 0) as total_km')
        )
        ->groupBy('v.id', 'v.plate')
        ->orderByDesc('total_trips')
        ->get();

    return response()->json([
        'message' => 'Uso de flotilla por periodo',
        'data' => $report
    ], 200);
    }
    public function driverHistory(User $user)
    {
    $history = DB::table('users as u')

        ->leftJoin('trips as t', function ($join) {
            $join->on('u.id', '=', 't.user_id')
                 ->whereNull('t.deleted_at');
        })

        ->leftJoin('vehicles as v', 'v.id', '=', 't.vehicle_id')

        ->leftJoin('requests as r', function ($join) {
            $join->on('u.id', '=', 'r.user_id')
                 ->whereNull('r.deleted_at');
        })

        ->leftJoin('vehicles as vr', 'vr.id', '=', 'r.vehicle_id')

        ->where('u.id', $user->id)

        ->select(
            'u.id as driver_id',
            'u.name as driver_name',

            // Trips
            't.id as trip_id',
            't.status as trip_status',
            't.departure_time',
            't.return_time',
            'v.plate as trip_vehicle',

            // Requests
            'r.id as request_id',
            'r.status as request_status',
            'r.start_date',
            'r.end_date',
            'vr.plate as request_vehicle'
        )

        ->orderByDesc('t.id')
        ->get();

    return response()->json([
        'message' => 'Historial del chofer',
        'data' => $history
    ], 200);
    }
}