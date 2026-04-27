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
    public function index(Request $request)
    {
        $status = $request->get('status_filter', 'available');
        
        $query = Vehicle::query();

        if ($status === 'all') {
            $query->withTrashed(); 
        } 
        elseif ($status === 'inactive') {
            $query->onlyTrashed(); 
        } 
        else {

            $query->where('status', $status)
                ->whereNull('deleted_at'); 
        }

        $vehicles = $query->latest()->paginate(10);

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
            'message' => 'Vehiculo creado correctamente.',
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
            'message' => 'Vehiculo encontrado correctamente.',
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
            'message' => 'Vehiculo actualizado correctamente.',
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

    public function restore(Vehicle $vehicle)
    {
        if (! $vehicle->trashed()) {
            return response()->json([
                'message' => 'Vehiculo no encontrado o no está eliminado.',
            ], 404);
        }

        $vehicle->restore();

        return response()->json([
            'message' => 'Vehiculo reactivado correctamente.',
            'data' => $vehicle->fresh(),
        ], 200);
    }
    
}
