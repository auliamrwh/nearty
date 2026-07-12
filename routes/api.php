<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TitipanController;
use App\Http\Controllers\Api\UlasanController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', fn (\Illuminate\Http\Request $r) => $r->user());

    // Resource 1: Titipan (CRUD JSON)
    Route::apiResource('titipans', TitipanController::class);

    // Resource 2: Ulasan (CRUD JSON)
    Route::apiResource('ulasans', UlasanController::class)->except(['store']);
    Route::post('/titipans/{titipan}/ulasans', [UlasanController::class, 'store']);
});
