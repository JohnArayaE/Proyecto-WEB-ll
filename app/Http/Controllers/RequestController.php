<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Models\Request as RequestModel;
use App\Models\Vehicle;

class RequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $request = RequestModel::whereNull('deleted_at')->latest()->paginate(10);

        return response()->json([
            'message' => 'Listado de Solicitud',
            'data' => $request,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequestRequest $request)
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
                'message' => 'El vehículo ya está ocupado.'
            ], 422);
        }

        $vehicle->status = 'unavailable';
        $vehicle->save();

        $newRequest = RequestModel::create($request->validated());

        return response()->json([
            'message' => 'Solicitud Creado Correctamente',
            'data' => $request
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(RequestModel $request)
    {
        //
        //dd($request);
        return response()->json([
            'message' => 'Solicitud encontrado correctamente.',
            'data' => $request,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequestRequest $Uptaderequest, RequestModel $request)
    {
        //
        $vehicle = Vehicle::find($Uptaderequest->vehicle_id ?? $request->vehicle_id);

        if ($vehicle->status === 'under maintenance') {
            return response()->json([
                'message' => 'El vehículo está en mantenimiento.'
            ], 422);
        }

        if ($vehicle->status === 'unavailable' && $vehicle->id != $request->vehicle_id) {
            return response()->json([
                'message' => 'El vehículo ya está ocupado.'
            ], 422);
        }

        $request->update($Uptaderequest->validated());

        return response()->json([
            'message' => 'Solicitud actualizado correctamente.',
            'data' => $request->load('vehicle')->load('user')->load('approver'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestModel $request)
    {
        //

        $vehicle = $request->vehicle;

        $request->delete();

        if ($vehicle) {
            $vehicle->status = 'available';
            $vehicle->save();
            $request->status = 'rejected';
            $request->save();
        }

        return response()->json([
            'message' => 'Solicitud eliminado correctamente',
        ], 200);
    }

    public function restore(RequestModel $request)
    {
        if (! $request->trashed()) {
            return response()->json([
                'message' => 'Asignación no encontrada o no está eliminada.',
            ], 404);
        }

        $vehicle = $request->vehicle;

        if ($vehicle && $vehicle->status !== 'available') {
            return response()->json([
                'message' => 'El vehículo no está disponible para restaurar la asignación.'
            ], 422);
        }

        $request->restore();

        if ($vehicle) {
            $vehicle->status = 'unavailable';
            $vehicle->save();
            $request->status = 'active';
            $request->save();
        }
        $request->restore();

        return response()->json([
            'message' => 'Vehiculo reactivado correctamente.',
            'data' => $request->load('vehicle')->load('user')->load('approver'),
        ], 200);
    }

    public function showWithUser(RequestModel $request)
    {
        //
        return response()->json([
            'message' => 'Solicitud encontrado correctamente.',
            'data' => $request->load('user')->load('approver'),
        ], 200);
    }

    public function showWithVehicle(RequestModel $request)
    {
        //
        return response()->json([
            'message' => 'Solicitud encontrado correctamente.',
            'data' => $request->load('vehicle'),
        ], 200);
    }

    public function showWithAll(RequestModel $request)
    {
        //
        return response()->json([
            'message' => 'Solicitud encontrado correctamente.',
            'data' => $request->load('vehicle')->load('user')->load('approver'),
        ], 200);
    }
    
    public function accepted(RequestModel $request)
    {
        if ($request->status !== 'pending') {
            return response()->json([
                'message' => 'La solicitud no se puede aprobar.'
            ], 422);
        }

        $vehicle = $request->vehicle;

        if (!$vehicle || $vehicle->status !== 'available') {
            return response()->json([
                'message' => 'El vehículo no está disponible.'
            ], 422);
        }

        $vehicle->status = 'unavailable';
        $vehicle->save();

        $request->status = 'approved';
        $request->save();

        return response()->json([
            'message' => 'Solicitud aprobada correctamente.',
            'data' => $request->load(['vehicle', 'user', 'approver']),
        ], 200);
    }

    public function rejected(RequestModel $request)
    {
        if ($request->status !== 'pending') {
            return response()->json([
                'message' => 'La solicitud no se puede rechazar.'
            ], 422);
        }

        $vehicle = $request->vehicle;

        if ($vehicle) {
            $vehicle->status = 'available';
            $vehicle->save();
        }

        $request->status = 'rejected';
        $request->save();

        return response()->json([
            'message' => 'Solicitud rechazada correctamente.',
            'data' => $request->load(['vehicle', 'user', 'approver']),
        ], 200);
    }

}
