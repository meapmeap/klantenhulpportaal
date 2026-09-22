<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');