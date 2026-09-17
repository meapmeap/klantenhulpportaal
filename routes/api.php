<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

//normale user kan alleen eigen account zien en aanpassen
Route::get('/profile', [UserController::class, 'profile']) ->middleware('auth:sanctum');
Route::post('/register', [UserController::class, 'register']);
//admin kan alle accounts zien, aanpassen en admin maken
Route::get('/users', [UserController::class, 'index']) ->middleware('auth:sanctum');
Route::post('/users', [UserController::class, 'store']) ->middleware('auth:sanctum');