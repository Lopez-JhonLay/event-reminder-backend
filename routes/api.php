<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EventController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::get('/user', [AuthController::class, 'user']);

	// Event routes
	Route::get('/events', [EventController::class, 'index']);
	Route::post('/events', [EventController::class, 'store']);
	Route::patch('/events/{event}', [EventController::class, 'update']);
	Route::get('/events/{event}', [EventController::class, 'show']);
	Route::delete('/events/{event}', [EventController::class, 'destroy']);
});
