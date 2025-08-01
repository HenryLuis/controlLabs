<?php

// --- Importaciones de Clases ---
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClassroomController;
use App\Http\Controllers\Api\V1\LabSessionController;
use App\Http\Controllers\Api\V1\SubjectController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
| Este archivo es la única fuente de verdad para las rutas y la seguridad
| de la API v1.
*/

// --- Ruta de Login Pública ---
Route::post('/login', [AuthController::class, 'login']);

// --- Grupo de Rutas Protegidas por Autenticación General ---
Route::middleware('auth:sanctum')->group(function () {

    // --- Rutas de Usuario y Sesión ---
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // --- Recurso de API para Aulas ---
    Route::apiResource('classrooms', ClassroomController::class)
         ->middleware('permission:manage-classrooms');

    // --- Recurso de API para Materias ---
    Route::apiResource('subjects', SubjectController::class)
         ->middleware('permission:manage-subjects');

    // --- Recurso de API para Sesiones de Laboratorio (con permisos granulares) ---
    Route::get('/lab-sessions', [LabSessionController::class, 'index'])->middleware('permission:view-all-lab-sessions');
    Route::get('/lab-sessions/{labSession}', [LabSessionController::class, 'show'])->middleware('permission:view-all-lab-sessions');
    Route::post('/lab-sessions', [LabSessionController::class, 'store'])->middleware('permission:create-lab-session');

    Route::post('/lab-sessions/{labSession}/review', [LabSessionController::class, 'markAsReviewed'])
         ->name('lab-sessions.review')
         ->middleware('permission:review-lab-session');

});
