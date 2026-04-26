<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Vehicle;
use App\Http\Requests\StoreAssignmentRequest;
use App\Http\Requests\UpdateAssignmentRequest;

class AssignmentController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with([
            'driver:id,name',
            'vehicle:id,plate,status',
            'assignedBy:id,name'
        ])->whereNull('deleted_at')
          ->latest()
          ->paginate(10);

        return response()->json([
            'message' => 'Listado de asignaciones',
            'data' => $assignments,
        ], 200);
    }

    public function store(StoreAssignmentRequest $request)
    {
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

        $assignment = Assignment::create($request->validated());

        $vehicle->status = 'unavailable';
        $vehicle->save();

        return response()->json([
            'message' => 'Asignación creada correctamente',
            'data' => $assignment->load([
                'driver:id,name',
                'vehicle:id,plate,status',
                'assignedBy:id,name'
            ])
        ], 201);
    }

    public function show(Assignment $assignment)
    {
        return response()->json([
            'message' => 'Asignación encontrada correctamente.',
            'data' => $assignment->load([
                'driver:id,name',
                'vehicle:id,plate,status',
                'assignedBy:id,name'
            ]),
        ], 200);
    }

    public function update(UpdateAssignmentRequest $request, Assignment $assignment)
    {
        $vehicle = Vehicle::find($request->vehicle_id ?? $assignment->vehicle_id);

        if ($vehicle->status === 'under maintenance') {
            return response()->json([
                'message' => 'El vehículo está en mantenimiento.'
            ], 422);
        }

        if ($vehicle->status === 'unavailable' && $vehicle->id !== $assignment->vehicle_id) {
            return response()->json([
                'message' => 'El vehículo ya está ocupado.'
            ], 422);
        }

        $assignment->update($request->validated());

        return response()->json([
            'message' => 'Asignación actualizada correctamente.',
            'data' => $assignment->load([
                'driver:id,name',
                'vehicle:id,plate,status',
                'assignedBy:id,name'
            ]),
        ], 200);
    }

    public function destroy(Assignment $assignment)
    {
        $vehicle = $assignment->vehicle;

        $assignment->delete();

        if ($vehicle) {
            $vehicle->status = 'available';
            $vehicle->save();
            $assignment->status = 'cancelled';
            $assignment->save();
        }

        return response()->json([
            'message' => 'Asignación eliminada correctamente',
        ], 200);
    }

    public function restore(Assignment $assignment)
    {
        if (! $assignment->trashed()) {
            return response()->json([
                'message' => 'Asignación no encontrada o no está eliminada.',
            ], 404);
        }

        $vehicle = $assignment->vehicle;

        if ($vehicle && $vehicle->status !== 'available') {
            return response()->json([
                'message' => 'El vehículo no está disponible para restaurar la asignación.'
            ], 422);
        }

        $assignment->restore();

        if ($vehicle) {
            $vehicle->status = 'unavailable';
            $vehicle->save();
            $assignment->status = 'active';
            $assignment->save();
        }

        return response()->json([
            'message' => 'Asignación reactivada correctamente.',
            'data' => $assignment->load([
                'driver:id,name',
                'vehicle:id,plate,status',
                'assignedBy:id,name'
            ]),
        ], 200);
    }

    public function inactive()
    {
        $assignments = Assignment::onlyTrashed()
            ->with([
                'driver:id,name',
                'vehicle:id,plate,status',
                'assignedBy:id,name'
            ])
            ->latest()
            ->paginate(10);

        return response()->json([
            'message' => 'Listado de asignaciones inactivas',
            'data' => $assignments,
        ], 200);
    }
}