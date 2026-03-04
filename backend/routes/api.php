<?php

use App\Http\Controllers\Api\Admin\TranslationsAdminController;
use App\Http\Controllers\Api\PublicTranslationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['ok' => true]));

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/i18n/translations', [PublicTranslationsController::class, 'index']);

Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
    Route::get('/i18n/translations', [TranslationsAdminController::class, 'index'])
        ->middleware('can:i18n.edit');

    Route::post('/i18n/translations', [TranslationsAdminController::class, 'store'])
        ->middleware('can:i18n.edit');

    Route::post('/i18n/translations/{id}/approve', [TranslationsAdminController::class, 'approve'])
        ->middleware('can:i18n.approve');

    Route::get('/i18n/coverage', [TranslationsAdminController::class, 'coverage'])
        ->middleware('can:i18n.edit');
});
