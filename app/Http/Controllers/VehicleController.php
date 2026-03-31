<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVehicleRequest;

class VehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $vehicles = Vehicle::whereNull('deleted_at')->latest()->paginate(10);

        return response()->json([
            'message' => 'List of vehicles',
            'data' => $vehicles,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVehicleRequest $request)
    {
        //
        $vehicle = Vehicle::create($request->validated());

        $vehicle->save();

        return response()->json([
            'message' => 'Curso creado correctamente.',
            'data' => $vehicle,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Vehicle $vehicle)
    {
        //
        return response()->json([
             'message' => 'Curso encontrado correctamente.',
            'data' => $vehicle,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        //
        $vehicle->update($request->validated());

        return response()->json([
            'message' => 'Curso actualizado correctamente.',
            'data' => $vehicle,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vehicle $vehicle)
    {
        //
        $vehicle->delete();

        return response()->json([
            'message' => 'Vehiculo eliminado correctamente',
        ], 200);
    }

    public function restore($id)
    {
        $Vehicle = Vehicle::onlyTrashed()->find($id);

        if (!$Vehicle) {
            return response()->json([
                'message' => 'Vehiculo no encontrado.',
            ], 404);
        }

        $Vehicle->restore();

        return response()->json([
            'message' => 'Vehiculo restaurado correctamente.',
            'data' => $Vehicle,
        ], 200);
    }
}
