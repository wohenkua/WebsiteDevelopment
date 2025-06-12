<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StableDiffusionController;
use App\Http\Controllers\GithubAuthController;
use App\Http\Controllers\UserController;

Route::post('/login',[UserController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
Route::post('/profile', [UserController::class, 'profile'])->middleware('auth:sanctum');
Route::post('/logout',[UserController::class, 'logout'])->middleware('auth:sanctum');


Route::post('/',[StableDiffusionController::class, 'generate']);
Route::post('/status', [StableDiffusionController::class, 'status']);

Route::post('/convert', [\App\Http\Controllers\ImageController::class, 'convertImage']);