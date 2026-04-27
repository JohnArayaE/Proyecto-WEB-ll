<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceRequest;
use App\Http\Requests\UpdateMaintenanceRequest;
use App\Models\Maintenance;
use App\Models\Vehicle;
use Illuminate\Http\Request;


class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $maintenance = Maintenance::with('vehicle')->whereNull('deleted_at')->latest()->paginate(10);

        return response()->json([
            'message' => 'Listado de mantenimientos',
            'data' => $maintenance,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaintenanceRequest $request)
    {
        //
        $vehicle = Vehicle::find($request->vehicle_id);

        if ($vehicle->status === 'under maintenance') {
            return response()->json([
                'message' => 'El vehículo está en mantenimiento.'
            ], 422);
        }

        if ($vehicle->status === 'unavailable') {
            return response()->json([
                'message' => 'El vehículo está asignado.'
            ], 422);
        }
        $vehicle->status = 'under maintenance';
        $vehicle->save();

        $maintenance = Maintenance::create($request->validated());

        return response()->json([
            'message' => 'Solicitud Creado Correctamente',
            'data' => $maintenance->load('vehicle')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Maintenance $maintenance)
    {
        //
        return response()->json([
            'message' => 'Mantenimiento encontrado correctamente.',
            'data' => $maintenance->load('vehicle'),
        ], 200);

    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMaintenanceRequest $request, Maintenance $maintenance)
    {
        //
        $vehicle = Vehicle::find($request->vehicle_id ?? $maintenance->vehicle_id);

        if ($vehicle->status === 'unavailable') {
            return response()->json([
                'message' => 'El vehículo está en un mantenimiento.'
            ], 422);
        }

        if ($vehicle->status === 'under maintenance' && $vehicle->id !== $maintenance->vehicle_id) {
            return response()->json([
                'message' => 'El vehículo está ocupado.'
            ], 422);
        }

        $maintenance->update($request->validated());

        return response()->json([
            'message' => 'Mantenimiento actualizado correctamente.',
            'data' => $maintenance->load('vehicle'),
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        //
        $vehicle = $maintenance->vehicle;

        $maintenance->delete();

        if ($vehicle) {
            $vehicle->status = 'available';
            $vehicle->save();
            $maintenance->status = 'cancelled';
            $maintenance->save();
        }

        $maintenance->delete();

        return response()->json([
            'message' => 'Mantenimiento eliminado correctamente',
        ], 200);
    }

    public function restore(Maintenance $maintenance)
    {
        if (! $maintenance->trashed()) {
            return response()->json([
                'message' => 'Asignación no encontrada o no está eliminada.',
            ], 404);
        }

        $vehicle = $maintenance->vehicle;

        if ($vehicle && $vehicle->status !== 'available') {
            return response()->json([
                'message' => 'El vehículo no está disponible para restaurar la asignación.'
            ], 422);
        }

        $maintenance->restore();

        if ($vehicle) {
            $vehicle->status = 'unavailable';
            $vehicle->save();
            $maintenance->status = 'in_progress';
            $maintenance->save();
        }

        return response()->json([
            'message' => 'Asignación reactivada correctamente.',
            'data' => $maintenance->load('vehicle'),
        ], 200);
    }

    public function completed(Maintenance $maintenance)
    {
        if($maintenance->status === 'in_progress'){

            $vehicle = $maintenance->vehicle;
            $vehicle->status = 'available';
            $vehicle->save();

            $maintenance->status = 'completed';
            $maintenance->save();

            return response()->json([
                'message' => 'mantenimineto completado correctamente correctamente.',
                'data' => $maintenance->load('vehicle'),
            ], 200);
        } else {
            return response()->json([
                'message' => 'El mantenimiento no se puede completar.'
            ], 422);
        }
    }

}
