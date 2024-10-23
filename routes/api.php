<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'v1'], function () {
    Route::post('/register', [ApiController::class, 'register']);
    Route::post('/lucky', [ApiController::class, 'lucky']);
    Route::post('/history', [ApiController::class, 'history']);
});
