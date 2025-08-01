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

    // --- RUTAS DEL MÓDULO DE SESIONES DE LABORATORIO (REFACTORIZADAS) ---

    // Rutas generales para ver sesiones (protegidas por un permiso general)
    Route::get('/lab-sessions', [LabSessionController::class, 'index'])->middleware('permission:view-all-lab-sessions');
    Route::get('/lab-sessions/{labSession}', [LabSessionController::class, 'show'])->middleware('permission:view-all-lab-sessions');

    // Acción del Docente: Crear la cabecera de la sesión
    Route::post('/lab-sessions', [LabSessionController::class, 'store'])->middleware('permission:create-lab-session');

    // Acción del Docente: Cerrar la sesión
    Route::post('/lab-sessions/{labSession}/close', [LabSessionController::class, 'close']); // La autorización está en el controlador

    // Acción del Estudiante: Registrar su asistencia
    Route::post('/lab-sessions/{labSession}/attend', [LabSessionController::class, 'addAttendance'])->middleware('permission:create-lab-session');

    // Acción de Varios Roles: Añadir una observación
    Route::post('/lab-sessions/{labSession}/observations', [LabSessionController::class, 'addObservation']); // Es una ruta autenticada, abierta a cualquier rol

    // Acción de Control Interno: Marcar como revisado
    Route::post('/lab-sessions/{labSession}/review', [LabSessionController::class, 'markAsReviewed'])->middleware('permission:review-lab-session');

    // Acción de Varios Roles: Descargar el PDF
    Route::get('/lab-sessions/{labSession}/pdf', [LabSessionController::class, 'downloadPdf'])->middleware('permission:download-lab-session-pdf');

});
