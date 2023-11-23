<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/paginated', [UserController::class, 'paginated']);
Route::get('/', [UserController::class, 'list']);
Route::post('/', [UserController::class, 'add']);
Route::get('/{id}', [UserController::class, 'listById']);
Route::patch('/{id}', [UserController::class, 'edit']);
Route::delete('/{id}', [UserController::class, 'destroy']);

// Route::withoutMiddleware('auth:sanctum')->post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout']);