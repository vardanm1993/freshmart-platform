<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['ok' => true]));


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
