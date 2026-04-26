<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTripRequest;
use App\Http\Requests\UpdateTripRequest;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $trips = Trip::with('vehicle')->whereNull('deleted_at')->latest()->paginate(10);

        return response()->json([
            'message' => 'List of trips',
            'data' => $trips,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTripRequest $request)
    {
        //
        $vehicle = Trip::create($request->validated());

        $vehicle->save();

        return response()->json([
            'message' => 'Viaje creado correctamente.',
            'data' => $vehicle,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Trip $trip)
    {
        //
        return response()->json([
            'message' => 'Viaje encontrado correctamente.',
            'data' => $trip,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTripRequest $request, Trip $trip)
    {
        //
        $trip->update($request->validated());

        return response()->json([
            'message' => 'Viaje actualizado correctamente.',
            'data' => $trip,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Trip $trip)
    {
        //
        $trip->delete();

        return response()->json([
            'message' => 'Viaje eliminado correctamente',
        ], 200);
    }

    public function restore(Trip $trip)
    {
        if (! $trip->trashed()) {
            return response()->json([
                'message' => 'Viaje no encontrado o no está eliminado.',
            ], 404);
        }

        $trip->restore();

        return response()->json([
            'message' => 'Viaje reactivado correctamente.',
            'data' => $trip->fresh(),
        ], 200);
    }
}
