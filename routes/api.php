<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;

Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Restaurar usuario eliminado
Route::patch('users/{user}/restore', [UserController::class, 'restore'])
    ->withTrashed()
    ->middleware(['auth:sanctum', 'can:restore,user']);


// CRUD completo de usuarios
Route::apiResource('users', UserController::class)
    ->middleware('auth:sanctum')
    ->middlewareFor('index', 'can:viewAny,App\Models\User')
    ->middlewareFor('show', 'can:view,user')
    ->middlewareFor('store', 'can:create,App\Models\User')
    ->middlewareFor('update', 'can:update,user')
    ->middlewareFor('destroy', 'can:delete,user')
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Usuario no encontrado.',
        ], 404);
    });

Route::patch('vehicle/{vehicle}/restore', [VehicleController::class, 'restore'])
    ->withTrashed()
    ->middleware('auth:sanctum', 'can:restore,vehicle');

Route::apiResource('vehicle', VehicleController::class)
    ->middleware('auth:sanctum')
    ->middlewareFor('index', 'can:viewAny,App\Models\Vehicle')
    ->middlewareFor('show', 'can:view,vehicle')
    ->middlewareFor('store', 'can:create,App\Models\Vehicle')
    ->middlewareFor('update', 'can:update,vehicle')
    ->middlewareFor('destroy', 'can:delete,vehicle')
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Usuario no encontrado.',
        ], 404);
    });
