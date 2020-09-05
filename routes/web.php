<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Timeline\TimelineAjaxController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



Route::middleware(['auth'])->group(function () {
    Route::get('/', 'HomeController@index')
        ->name('home');

    Route::group(['prefix' => 'ajax/'], function () {
        Route::group(['prefix' => 'timeline/'], function () {
            Route::get('getdata', [TimelineAjaxController::class, 'getData']);
        });
    });
});

Auth::routes();
