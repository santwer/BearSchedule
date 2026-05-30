<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Passkeys\Http\Controllers\PasskeyRegistrationController;

Route::get('/passkeys', function (Request $request) {
    return $request->user()->passkeys()
        ->orderByDesc('created_at')
        ->get(['id', 'name', 'created_at']);
});

Route::post('/user/confirm-password', [ConfirmablePasswordController::class, 'store']);

Route::middleware('password.confirm')->group(function () {
    Route::get('/user/passkeys/options', [PasskeyRegistrationController::class, 'index']);
    Route::post('/user/passkeys', [PasskeyRegistrationController::class, 'store']);
    Route::delete('/user/passkeys/{passkey}', [PasskeyRegistrationController::class, 'destroy']);
});

Route::get('/meta', [\App\Http\Controllers\Api\MetaController::class, 'index']);
Route::get('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'index']);
Route::put('/settings', [\App\Http\Controllers\Api\SettingsController::class, 'update']);
Route::get('/timeline/{project}', [\App\Http\Controllers\Api\TimelineController::class, 'index']);
Route::delete('/timeline/{project}', [\App\Http\Controllers\Api\TimelineController::class, 'destroy']);
Route::post('/timeline/{project}/archive', [\App\Http\Controllers\Api\TimelineController::class, 'archive']);
Route::get('/timeline/{project}/settings', [\App\Http\Controllers\Api\TimelineController::class, 'settings']);
Route::get('/timeline/{project}/excel', [\App\Http\Controllers\Api\ExcelExportController::class, 'index']);
Route::post('/timeline/{project}/settings', [\App\Http\Controllers\Api\TimelineController::class, 'updateSetting']);
Route::get('/share/{project}', [\App\Http\Controllers\Api\ShareController::class, 'index']);
Route::post('/role', [\App\Http\Controllers\Api\ShareController::class, 'saveRole']);
Route::delete('/role', [\App\Http\Controllers\Api\ShareController::class, 'deleteRole']);
Route::post('/items', [\App\Http\Controllers\Timeline\TimelineAjaxController::class, 'setItem']);
Route::delete('/item/{id}', [\App\Http\Controllers\Timeline\TimelineAjaxController::class, 'destroyItem']);
Route::post('/groups', [\App\Http\Controllers\Timeline\TimelineAjaxController::class, 'setGroup']);
Route::post('/groups-order', [\App\Http\Controllers\Timeline\TimelineAjaxController::class, 'setGroupOrder']);
Route::delete('/groups/{id}', [\App\Http\Controllers\Timeline\TimelineAjaxController::class, 'destroyGroup']);
Route::get('/search-person/{project}', \App\Http\Controllers\Api\SearchUserController::class);
Route::get('/projects/{project}/mcp-tokens', [\App\Http\Controllers\Api\McpTokenController::class, 'index']);
Route::post('/projects/{project}/mcp-tokens', [\App\Http\Controllers\Api\McpTokenController::class, 'store']);
Route::delete('/projects/{project}/mcp-tokens/{tokenId}', [\App\Http\Controllers\Api\McpTokenController::class, 'destroy']);
