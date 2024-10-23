<?php

use App\Http\Controllers\CustomPageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Main');
});

Route::get('/custom-page/page={page}', [CustomPageController::class, 'show']);
Route::get('/regenerete/page={page}', [CustomPageController::class, 'regenerate']);
Route::get('/deactivate/page={page}', [CustomPageController::class, 'deactivate']);
