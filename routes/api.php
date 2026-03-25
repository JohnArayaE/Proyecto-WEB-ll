<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VehicleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('vehicle', VehicleController::class)
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'vehiculo no encontrado.',
        ], 404);
    });

