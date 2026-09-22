<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

//normale user kan alleen eigen account zien en aanpassen
Route::get('/profile', [UserController::class, 'profile']) ->middleware('auth:sanctum');
Route::post('/register', [UserController::class, 'register']);
//admin kan alle accounts zien, aanpassen en admin maken
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/my-tickets', [TicketController::class, 'myTickets']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
});
