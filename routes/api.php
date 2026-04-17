<?php

use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RequestController;

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
            'message' => 'Vehiculo no encontrado.',
        ], 404);
    });

Route::patch('request/{request}/restore', [RequestController::class, 'restore'])
    ->withTrashed()
    ->middleware('auth:sanctum', 'can:restore,vehicle');

Route::apiResource('request', RequestController::class)
    ->middleware('auth:sanctum')
    ->middlewareFor('index', 'can:viewAny,App\Models\Request')
    ->middlewareFor('show', 'can:view,resquest')
    ->middlewareFor('store', 'can:create,App\Models\Request')
    ->middlewareFor('update', 'can:update,request')
    ->middlewareFor('destroy', 'can:delete,request')
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Solicitud no encontrado.',
        ], 404);
    }); 
   
Route::post('requests/{request}/restore', [RequestController::class, 'restore'])
    ->middleware(['auth:sanctum', 'can:restore,request']);

Route::get('requests/{request}/with-user', [RequestController::class, 'showWithUser'])
    ->middleware(['auth:sanctum', 'can:showWithUser,request']);

Route::get('requests/{request}/with-vehicle', [RequestController::class, 'showWithVehicle'])
    ->middleware(['auth:sanctum', 'can:showWithVehicle,request']);

Route::get('requests/{request}/with-all', [RequestController::class, 'showWithAll'])
    ->middleware(['auth:sanctum', 'can:showWithAll,request']); 
    

Route::patch('maintenance/{maintenance}/restore', [Maintenance::class, 'restore'])
    ->withTrashed()
    ->middleware('auth:sanctum', 'can:restore,vehicle');

Route::apiResource('maintenance', RequestController::class)
    ->middleware('auth:sanctum')
    ->middlewareFor('index', 'can:viewAny,App\Models\Maintenance')
    ->middlewareFor('show', 'can:view,maintenance')
    ->middlewareFor('store', 'can:create,App\Models\Maintenance')
    ->middlewareFor('update', 'can:update,maintenance')
    ->middlewareFor('destroy', 'can:delete,maintenance')
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Mantenimeintos no encontrado.',
        ], 404);
    }); 