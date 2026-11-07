<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SurveyReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dedicated Survey Condition Report (Printable & View 1:1 Legacy result.php)
Route::get('/surveys/{survey}/report', [SurveyReportController::class, 'show'])
    ->name('surveys.report');

// Dedicated secure document streaming & missing file handler
Route::get('/document/attachment/{filename}', [DocumentController::class, 'show'])
    ->where('filename', '.*')
    ->name('document.attachment');

Route::get('/storage/attachment/{filename}', [DocumentController::class, 'show'])
    ->where('filename', '.*');

Route::get('/storage/attachments/{filename}', [DocumentController::class, 'show'])
    ->where('filename', '.*');
