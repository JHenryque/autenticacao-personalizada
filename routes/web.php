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

Route::get('/home', [MainController::class, 'home'])->name('home');
