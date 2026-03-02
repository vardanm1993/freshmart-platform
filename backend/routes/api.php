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

Route::get('/admin/i18n/translations', [TranslationsAdminController::class, 'index']);
Route::post('/admin/i18n/translations', [TranslationsAdminController::class, 'store']);
Route::post('/admin/i18n/translations/{id}/approve', [TranslationsAdminController::class, 'approve']);
