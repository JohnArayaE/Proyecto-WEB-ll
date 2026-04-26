<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Restaurar usuario eliminado
Route::patch('users/{id}/restore', [UserController::class, 'restore']);
Route::get('users/inactive', [UserController::class, 'inactive'])
    ->middleware(['auth:sanctum', 'can:viewAny,App\Models\User']);
// CRUD completo de usuarios
Route::apiResource('users', UserController::class)
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Usuario no encontrado.',
        ], 404);
    });