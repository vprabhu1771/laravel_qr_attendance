<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

use App\Http\Controllers\api\UserController;

Route::post('/user/isMobileNumberRegistered', [UserController::class, 'isMobileNumberRegistered']);


use App\Http\Controllers\api\AuthController;

Route::post('/login', [AuthController::class, 'login']);

// Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/user', [AuthController::class, 'user']);

    Route::post('/upload-profile-pic', [AuthController::class, 'upload']);
});

Route::post('/mark-attendance/{eventId}', [QRController::class, 'markAttendance']);

use App\Http\Controllers\api\AttendanceController;

Route::get('/attendances', [AttendanceController::class, 'index']);


// Route::post('/mark-attendance/{eventId}', [AttendanceController::class, 'markAttendance']);

Route::middleware('auth:sanctum')->group(function () {
    // Routes for authenticated users only

    Route::post('/mark-attendance/{eventId}', [AttendanceController::class, 'markAttendance']);

    // Mark attendance for the current user (present or absent)
    // Route::post('/attendance', [AttendanceController::class, 'markAttendance']);

    // Get attendance history for the current user
    Route::get('/attendance/history', [AttendanceController::class, 'getAttendanceHistory']);

    
});

Route::post('/attendance/generate-qrcode', [AttendanceController::class, 'generateQrCode']);