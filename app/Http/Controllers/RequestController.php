<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRequestRequest;
use App\Http\Requests\UpdateRequestRequest;
use App\Models\Request as RequestModel;

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
        $request = RequestModel::create($request->validated());

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
        $request->update($Uptaderequest->validated());

        return response()->json([
            'message' => 'Solicitud actualizado correctamente.',
            'data' => $request,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RequestModel $request)
    {
        //
        $request->delete();

        return response()->json([
            'message' => 'Solicitud eliminado correctamente',
        ], 200);
    }

    public function restore(RequestModel $request)
    {
        if (! $request->trashed()) {
            return response()->json([
                'message' => 'Vehiculo no encontrado o no está eliminado.',
            ], 404);
        }

        $request->restore();

        return response()->json([
            'message' => 'Vehiculo reactivado correctamente.',
            'data' => $request,
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
    
}
