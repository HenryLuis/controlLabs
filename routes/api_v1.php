<?php

// --- PASO 1: IMPORTAR LAS CLASES NECESARIAS ---
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ClassroomController;
use Illuminate\Support\Facades\Route;
// ------------------------------------------

/*
|--------------------------------------------------------------------------
| API V1 Routes
|--------------------------------------------------------------------------
|
| Aquí registramos todas las rutas para la versión 1 de nuestra API.
| Estas rutas son cargadas automáticamente por bootstrap/app.php dentro
| de un grupo que ya aplica el middleware 'web' y el prefijo 'api/v1'.
|
*/

// --- RUTA DE LOGIN PÚBLICA (No necesita autenticación) ---
Route::post('/login', [AuthController::class, 'login']);

// --- GRUPO DE RUTAS PROTEGIDAS (Requieren autenticación) ---
Route::middleware('auth:sanctum')->group(function () {

    // Ruta para obtener los datos del usuario logueado
    Route::get('/user', [AuthController::class, 'user']);

    // Ruta para cerrar la sesión del usuario logueado
    Route::post('/logout', [AuthController::class, 'logout']);

    // Recurso de API para Aulas, protegido por permisos específicos
    Route::apiResource('classrooms', ClassroomController::class)
         ->middleware('permission:manage-classrooms');

    // Aquí, en el futuro, añadirás las otras rutas protegidas de la v1:
    // Route::apiResource('subjects', SubjectController::class)->middleware(...);

});
