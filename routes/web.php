<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

use App\Http\Controllers\QRController;

Route::get('/generate-qr-code/{eventId}', [QRController::class, 'generateQrCode'])->name('generateQrCode');

use App\Http\Controllers\ReportController;

Route::get('/generate-report', [ReportController::class, 'generateReport']);