<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StableDiffusionController;
use App\Http\Controllers\GithubAuthController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');




Route::post('/',[StableDiffusionController::class, 'generate']);
Route::post('/status', [StableDiffusionController::class, 'status']);
