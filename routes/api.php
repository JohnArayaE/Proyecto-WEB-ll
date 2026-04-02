<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

// Restaurar usuario eliminado
Route::patch('users/{id}/restore', [UserController::class, 'restore']);

// CRUD completo de usuarios
Route::apiResource('users', UserController::class)
    ->missing(function (Request $request) {
        return response()->json([
            'message' => 'Usuario no encontrado.',
        ], 404);
    });