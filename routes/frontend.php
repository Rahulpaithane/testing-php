<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CacheController;


// Route Cache Handler
Route::controller(CacheController::class)->group(function () {
    Route::get('cache', 'caches');
    Route::get('cache-clear', 'clearCaches');
    Route::get('run-queue', 'runQueue');
    Route::get('restart-queue', 'restartQueue');
});

Route::get('/', function () {
    return view('frontend/welcome');
});

?>