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

//middleware('localization')
Route::get('json/localization', [\App\Http\Controllers\LocaleController::class, 'getLocale']);
Route::middleware(['auth'])->group(function () {
    Route::group(['prefix' => 'ajax/'], function () {
        Route::group(['prefix' => 'timeline/'], function () {
            Route::get('getdata', [TimelineAjaxController::class, 'getData']);
            Route::get('getShareLink', [TimelineAjaxController::class, 'getShareLink']);
            Route::get('deleteShareLink', [TimelineAjaxController::class, 'deleteShareLink']);
            Route::put('item', [TimelineAjaxController::class, 'setItem']);
            Route::delete('item', [TimelineAjaxController::class, 'destroyItem']);
            Route::put('group', [TimelineAjaxController::class, 'setGroup']);
            Route::put('grouporder', [TimelineAjaxController::class, 'setGroupOrder']);
            Route::delete('group', [TimelineAjaxController::class, 'destroyGroup']);
        });
        Route::group(['prefix' => 'jira/', 'middleware' => 'jira'], function () {
            Route::get('issue/search', [\App\Http\Controllers\Timeline\JiraAjaxController::class, 'getIssues']);
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
Route::any('/', [\App\Http\Controllers\LocaleController::class, 'redirectMain']);
Route::group([
    'prefix' => '{locale?}',
    'where' => ['locale' => '[a-zA-Z]{2}'],
    'middleware' => 'setlocale'
], function () {
    Route::middleware(['auth'])->group(function () {
        Route::get('/', 'HomeController@index')
            ->name('home');
        Route::get('/settings', [\App\Http\Controllers\HomeController::class, 'settings'])->name('user.settings');
        Route::post('/settings',
            [\App\Http\Controllers\HomeController::class, 'saveSettings'])->name('user.settings.save');
        Route::post('/settings/delete',
            [\App\Http\Controllers\HomeController::class, 'deleteAccount'])->name('user.settings.delete');
        Route::get('project/create', [ProjectController::class, 'create'])->name('project.create');
        Route::get('project/{project}', [ProjectController::class, 'index'])->name('project.open');
        Route::post('project/{project}', [ProjectController::class, 'update'])->name('project.update');
        Route::post('project/{project}/delete', [ProjectController::class, 'destroy'])->name('project.delete');


        Route::group(['prefix' => 'jira/', 'middleware' => 'jira'], function () {
            Route::get('issue/{project}/{issue}',
                [\App\Http\Controllers\Timeline\JiraAjaxController::class, 'redirectIssue']);
        });
    });


//Auth Routes
    Route::group(['prefix' => 'auth/'], function () {
        Route::get('microsoft/', [LoginMSController::class, 'oauth'])->name('auth.microsoft');
        Route::get('microsoft/callback', [LoginMSController::class, 'callback'])->name('auth.microsoft.callback');
    });

    Route::get('/disclaimer',
        [\App\Http\Controllers\Login\LoginController::class, 'disclaimer'])->name('disclaimer');
    Route::get('/privacy', [\App\Http\Controllers\Login\LoginController::class, 'privacy'])->name('privacy');

    // Authentication Routes...
    Route::get('login', [
        'as' => 'login',
        'uses' => 'Auth\LoginController@showLoginForm'
    ]);
    Route::post('login', [
        'as' => '',
        'uses' => 'Auth\LoginController@login'
    ]);
    Route::post('logout', [
        'as' => 'logout',
        'uses' => 'Auth\LoginController@logout'
    ]);

    // Password Reset Routes...
    Route::post('password/email', [
        'as' => 'password.email',
        'uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail'
    ]);
    Route::get('password/reset', [
        'as' => 'password.request',
        'uses' => 'Auth\ForgotPasswordController@showLinkRequestForm'
    ]);
    Route::post('password/reset', [
        'as' => 'password.update',
        'uses' => 'Auth\ResetPasswordController@reset'
    ]);
    Route::get('password/reset/{token}', [
        'as' => 'password.reset',
        'uses' => 'Auth\ResetPasswordController@showResetForm'
    ]);

    // Registration Routes...
    Route::get('register', [
        'as' => 'register',
        'uses' => 'Auth\RegisterController@showRegistrationForm'
    ]);
    Route::post('register', [
        'as' => '',
        'uses' => 'Auth\RegisterController@register'
    ]);

});
Route::fallback([\App\Http\Controllers\LocaleController::class, 'redirect']);

