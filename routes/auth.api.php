<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/meta', [\App\Http\Controllers\Api\MetaController::class, 'index']);
Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
Route::put('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'update']);
