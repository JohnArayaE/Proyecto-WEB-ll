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
        $maintenance = Maintenance::create($request->validated());

        return response()->json([
            'message' => 'Solicitud Creado Correctamente',
            'data' => $maintenance
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
        $maintenance->update($request->validated());

        return response()->json([
            'message' => 'Mantenimiento actualizado correctamente.',
            'data' => $maintenance,
        ], 200);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Maintenance $maintenance)
    {
        //
        $maintenance->delete();

        return response()->json([
            'message' => 'Mantenimiento eliminado correctamente',
        ], 200);
    }

    public function restore(Maintenance $maintenance)
    {
        if (! $maintenance->trashed()) {
            return response()->json([
                'message' => 'Mantenimiento no encontrado o no está eliminado.',
            ], 404);
        }

        $maintenance->restore();

        return response()->json([
            'message' => 'Mantenimeinto reactivado correctamente.',
            'data' => $maintenance->fresh(),
        ], 200);
    }
}
