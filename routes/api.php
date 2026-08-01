<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Importation des contrôleurs
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\EtudiantController;
use App\Http\Controllers\Api\V1\CoursController;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function() {

    // --- Routes Publiques ---
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // --- Routes Protégées (Sanctum) ---
    Route::middleware('auth:sanctum')->group(function() {
        
        // Profil et Déconnexion
        Route::get('/auth/me', [AuthController::class, 'me']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Ressources CRUD (Génère automatiquement index, store, show, update, destroy)
        Route::apiResource('etudiants', EtudiantController::class);
        Route::apiResource('cours', CoursController::class);

        // Many-to-Many : Gestion des inscriptions aux cours
        // Correction : On utilise {etudiant} pour correspondre à l'injection de dépendance du Controller
        Route::post('/etudiants/{etudiant}/cours/attach', [EtudiantController::class, 'attach']);
        Route::post('/etudiants/{etudiant}/cours/detach', [EtudiantController::class, 'detach']);
        Route::post('/etudiants/{etudiant}/cours/sync', [EtudiantController::class, 'sync']);
    });

});