<?php

use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

Route::get('/',function () {
    try {
        DB::connection()->getPdo();
        echo "connectado la base de datos";

    } catch (\Throwable $th) {
        echo "error: " . $th->getMessage();
    }
});

Route::get('/test', function(){
    echo 'teste';
})->name('test')->middleware('auth');

Route::get('/home', [MainController::class, 'home'])->name('home');

Route::get('/login', function () {
    echo 'formulario de ingreso';
})->name('login');

Route::middleware('guest')->group(function () {
    Route::get('/register', function () {
        echo 'formulario de registro';
    })->name('register');
});

Route::get('/registro',function(){ echo 'formulario de registro';})->name('registro')->middleware('guest');
