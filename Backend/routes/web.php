<?php

use Illuminate\Support\Facades\Route;

use app\Http\Controllers\StableDiffusionController;


Route::post('/',[StableDiffusionController::class, 'genearte']);