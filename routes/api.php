<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\Auth\PasswordResetController;

//authenticatie
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']) ->middleware('auth:sanctum');

//wachtwoord reset, mogelijk zonder log in
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLink']);
Route::post('/reset-password', [PasswordResetController::class, 'resetPassword']);

//normale user kan alleen eigen account zien en aanpassen
Route::get('/profile', [UserController::class, 'profile']) ->middleware('auth:sanctum');
//routes die alleen gebruikt kunnen worden als je bent ingelogd als admin
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    //users
    Route::get('/users/admins', [UserController::class, 'admins']);
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    //categories
    Route::post('/categories', [CategorieController::class, 'store']);
    Route::put('/categories/{categorie}', [CategorieController::class, 'update']);
    Route::delete('/categories/{categorie}', [CategorieController::class, 'destroy']);
});

//routes die iedereen kan gebruiken die is ingelogd
Route::middleware('auth:sanctum')->group(function () {
    //categories
    Route::get('/categories', [CategorieController::class, 'index']);
    //tickets
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::put('/tickets/{ticket}', [TicketController::class, 'update']);
});
