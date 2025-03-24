<?php

use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Modalidade\ModalidadeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/api-user', UserApiController::class);
Route::apiResource('/api-modalidade', ModalidadeController::class);
