<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/meta', [\App\Http\Controllers\Api\MetaController::class, 'index']);
Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
Route::put('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'update']);
Route::get('/timeline/{project}', [\App\Http\Controllers\Api\TimelineController::class, 'index']);
Route::get('/timeline/{project}/settings', [\App\Http\Controllers\Api\TimelineController::class, 'settings']);
Route::post('/timeline/{project}/settings', [\App\Http\Controllers\Api\TimelineController::class, 'updateSetting']);
Route::get('/share/{project}', [\App\Http\Controllers\Api\ShareController::class, 'index']);
Route::post('/role', [\App\Http\Controllers\Api\ShareController::class, 'saveRole']);
Route::delete('/role', [\App\Http\Controllers\Api\ShareController::class, 'deleteRole']);
Route::post('/items', [\App\Http\Controllers\Timeline\TimelineAjaxController::class, 'setItem']);
Route::post('/groups', [\App\Http\Controllers\Timeline\TimelineAjaxController::class, 'setGroup']);
Route::get('/search-person/{project}', \App\Http\Controllers\Api\SearchUserController::class);
