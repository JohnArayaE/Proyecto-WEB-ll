<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RouteController;

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

// Restaurar ruta eliminada
Route::patch('routes/{route}/restore', [RouteController::class, 'restore'])
    ->withTrashed()
    ->middleware(['auth:sanctum', 'can:restore,route']);


// CRUD completo de rutas
Route::get('routes/inactive', [RouteController::class, 'inactive'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\Route']);

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
