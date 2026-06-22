<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\DosenController;
use App\Http\Controllers\Api\SlotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', fn (Request $r) => $r->user());

    Route::get('/dosen', [DosenController::class, 'index']);
    Route::get('/slots', [SlotController::class, 'index']);

    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/me', [BookingController::class, 'mine']);
    Route::patch('/bookings/{booking}/approve', [BookingController::class, 'approve']);
});
