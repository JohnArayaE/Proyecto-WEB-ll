<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Requests\ReportAvailableVehiclesRequest;

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
}