<?php

use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\TripController;
use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\RouteController;

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
            'message' => 'Usuario no encontrado.',
        ], 404);
    });
// Restaurar ruta eliminada
Route::patch('routes/{route}/restore', [RouteController::class, 'restore'])
    ->withTrashed()
    ->middleware(['auth:sanctum', 'can:restore,route']);


// CRUD completo de rutas
Route::apiResource('routes', RouteController::class)
    ->middleware('auth:sanctum')
    ->middlewareFor('index', 'can:viewAny,App\Models\Route')
    ->middlewareFor('show', 'can:view,route')
    ->middlewareFor('store', 'can:create,App\Models\Route')
    ->middlewareFor('update', 'can:update,route')
    ->middlewareFor('destroy', 'can:delete,route')
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Ruta no encontrada.',
        ], 404);
    });

Route::patch('request/{request}/restore', [RequestController::class, 'restore'])
    ->withTrashed()
    ->middleware('auth:sanctum', 'can:restore,request');

Route::apiResource('request', RequestController::class)
    ->middleware('auth:sanctum')
    ->middlewareFor('index', 'can:viewAny,App\Models\Request')
    ->middlewareFor('show', 'can:view,request')
    ->middlewareFor('store', 'can:create,App\Models\Request')
    ->middlewareFor('update', 'can:update,request')
    ->middlewareFor('destroy', 'can:delete,request')
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Solicitud no encontrado.',
        ], 404);
    }); 
   

Route::get('requests/{request}/with-user', [RequestController::class, 'showWithUser'])
    ->middleware(['auth:sanctum', 'can:showWithUser,request']);

Route::get('requests/{request}/with-vehicle', [RequestController::class, 'showWithVehicle'])
    ->middleware(['auth:sanctum', 'can:showWithVehicle,request']);

Route::get('requests/{request}/with-all', [RequestController::class, 'showWithAll'])
    ->middleware(['auth:sanctum', 'can:showWithAll,request']); 
    

Route::patch('maintenance/{maintenance}/restore', [MaintenanceController::class, 'restore'])
    ->withTrashed()
    ->middleware('auth:sanctum', 'can:restore,maintenance');

Route::apiResource('maintenance', MaintenanceController::class)
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

Route::patch('trip/{trip}/restore', [TripController::class, 'restore'])
    ->withTrashed()
    ->middleware('auth:sanctum', 'can:restore,trip');

Route::apiResource('trip', TripController::class)
    ->middleware('auth:sanctum')
    ->middlewareFor('index', 'can:viewAny,App\Models\Trip')
    ->middlewareFor('show', 'can:view,trip')
    ->middlewareFor('store', 'can:create,App\Models\Trip')
    ->middlewareFor('update', 'can:update,trip')
    ->middlewareFor('destroy', 'can:delete,trip')
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Viaje no encontrado.',
        ], 404);
    }); 