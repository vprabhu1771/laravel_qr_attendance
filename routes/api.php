<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\api\v2\UserController;

Route::post('/user/isMobileNumberRegistered', [UserController::class, 'isMobileNumberRegistered']);