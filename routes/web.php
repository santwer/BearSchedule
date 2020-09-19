<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Timeline\TimelineAjaxController;
use \App\Http\Controllers\Project\ProjectController;
use \App\Http\Controllers\AutoComplete\AutoCompleteController;
use \App\Http\Controllers\Share\ShareController;
use \App\Http\Controllers\Auth\LoginMSController;
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
    Route::get('/settings', [\App\Http\Controllers\HomeController::class, 'settings'])->name('user.settings');
    Route::post('/settings', [\App\Http\Controllers\HomeController::class, 'saveSettings'])->name('user.settings.save');
    Route::post('/settings/delete', [\App\Http\Controllers\HomeController::class, 'deleteAccount'])->name('user.settings.delete');
    Route::get('project/create', [ProjectController::class, 'create']);
    Route::get('project/{project}', [ProjectController::class, 'index'])->name('project.open');
    Route::post('project/{project}', [ProjectController::class, 'update'])->name('project.update');

    Route::group(['prefix' => 'ajax/'], function () {
        Route::group(['prefix' => 'timeline/'], function () {
            Route::get('getdata', [TimelineAjaxController::class, 'getData']);
            Route::get('getShareLink', [TimelineAjaxController::class, 'getShareLink']);
            Route::get('deleteShareLink', [TimelineAjaxController::class, 'deleteShareLink']);
            Route::put('item', [TimelineAjaxController::class, 'setItem']);
            Route::delete('item', [TimelineAjaxController::class, 'destroyItem']);
            Route::put('group', [TimelineAjaxController::class, 'setGroup']);
            Route::delete('group', [TimelineAjaxController::class, 'destroyGroup']);
        });
        Route::get('autocomplete/{controller}', [AutoCompleteController::class, 'index']);
    });
});
Route::group(['prefix' => 'share/'], function () {
    Route::get('{unique}/', [ShareController::class, 'index']);
    Route::get('{unique}/ajax/getdata', [ShareController::class, 'getData']);
    Route::get('{unique}/share.js', [ShareController::class, 'getShareJs']);
    Route::get('{unique}/share.css', [ShareController::class, 'getShareCss']);

});



//Auth Routes
Route::group(['prefix' => 'auth/'], function () {
    Route::get('microsoft/', [LoginMSController::class, 'oauth'])->name('auth.microsoft');
    Route::get('microsoft/callback', [LoginMSController::class, 'callback'])->name('auth.microsoft.callback');
});

Auth::routes();
