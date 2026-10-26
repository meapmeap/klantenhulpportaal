<?php

use App\Http\Controllers\TicketController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\ReactieController;
use App\Http\Controllers\NotitieController;

//authenticatie
Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);

//wachtwoord reset, mogelijk zonder log in
Route::controller(PasswordResetController::class)->group(function () {
    Route::post('/forgot-password', 'sendResetLink');
    Route::post('/reset-password', 'resetPassword');
});

//routes waarvoor je ingelogd moet zijn
Route::middleware('auth:sanctum')->group(function () {
    //authenticatie
    Route::post('/logout', [UserController::class, 'logout']);
    Route::get('/profile', [UserController::class, 'profile']);
    //categories
    Route::get('/categories', [CategorieController::class, 'index']);
    //tickets
    Route::controller(TicketController::class)->group(function () {
        Route::get('/tickets', 'index');
        Route::get('/tickets/{ticket}', 'show');
        Route::post('/tickets', 'store');
        Route::put('/tickets/{ticket}', 'update');
    });
    //reacties
    Route::controller(ReactieController::class)->group(function () {
        Route::get('/tickets/{ticket}/reacties', 'index');
        Route::post('/tickets/{ticket}/reacties', 'store');
        Route::put('/tickets/{ticket}/reacties/{reactie}', 'update');
    });

    //routes die alleen admins kunnen gebruiken
    Route::middleware('admin')->group(function () {
        //users
        Route::get('/users/admins', [UserController::class, 'admins']);
        Route::apiResource('users', UserController::class)->only(['index', 'store', 'update', 'destroy']);
        // Categorieën
        Route::controller(CategorieController::class)->group(function () {
            Route::post('/categories', 'store');
            Route::put('/categories/{categorie}', 'update');
            Route::delete('/categories/{categorie}', 'destroy');
        });
        // Notities
        Route::controller(NotitieController::class)->group(function () {
            Route::get('/tickets/{ticket}/notities', 'index');
            Route::post('/tickets/{ticket}/notities', 'store');
            Route::put('/tickets/{ticket}/notities/{notitie}', 'update');
            Route::delete('/tickets/{ticket}/notities/{notitie}', 'destroy');
        });
    });
});