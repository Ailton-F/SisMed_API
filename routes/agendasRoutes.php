<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgendaController;
use Illuminate\Support\Facades\Route;

Route::get('/paginated', [AgendaController::class, 'paginated']);
Route::get('/', [AgendaController::class, 'list']);
Route::post('/', [AgendaController::class, 'add']);
Route::get('/{id}', [AgendaController::class, 'listById']);
Route::patch('/{id}', [AgendaController::class, 'edit']);
Route::delete('/{id}', [AgendaController::class, 'destroy']);

// Route::withoutMiddleware('auth:sanctum')->post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout']);