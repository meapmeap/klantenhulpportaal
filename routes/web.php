<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');

Route::post('/login', [UserController::class, 'login']);
Route::post('/register', [UserController::class, 'register']);