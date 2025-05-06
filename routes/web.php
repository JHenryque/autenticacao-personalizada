<?php

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
