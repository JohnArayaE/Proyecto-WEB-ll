<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRouteRequest;
use App\Http\Requests\UpdateRouteRequest;
use App\Models\Route;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $routes = Route::whereNull('deleted_at')->latest()->paginate(10);

        return response()->json([
            'message' => 'Listado de rutas',
            'data' => $routes,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRouteRequest $request)
    {
        $route = Route::create($request->validated());

        return response()->json([
            'message' => 'Ruta creada correctamente',
            'data' => $route
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Route $route)
    {
        return response()->json($route, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRouteRequest $request, Route $route)
    {
        $route->update($request->validated());

        return response()->json([
            'message' => 'Ruta actualizada correctamente',
            'data' => $route->fresh(),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Route $route)
    {
        $route->delete(); // Soft delete

        return response()->json([
            'message' => 'Ruta eliminada lógicamente (soft delete).',
        ], 200);
    }

    /**
     * Restore a soft deleted resource.
     */
    public function restore(Route $route)
    {
        if (! $route->trashed()) {
            return response()->json([
                'message' => 'Ruta no encontrada o no está eliminada.',
            ], 404);
        }

        $route->restore();

        return response()->json([
            'message' => 'Ruta restaurada correctamente.',
            'data' => $route->fresh(),
        ], 200);
    }

    public function inactive()
{
    $routes = Route::onlyTrashed()
        ->latest()
        ->paginate(10);

    return response()->json([
        'message' => 'Listado de rutas inactivas',
        'data' => $routes,
    ], 200);
}
}