<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('guest')->group(function () {
    // login routes
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

    // registration routes
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store_user'])->name('store_user');

    //new user confirmation
    Route::get('/new_user_confirmation/{token}', [AuthController::class, 'new_user_confirmation'])->name('new_user_confirmation');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [MainController::class, 'home'])->name('home');

    // profile - change_password
    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    route::post('/profile', [AuthController::class, 'change_password'])->name('change_password');

    // logout
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});

