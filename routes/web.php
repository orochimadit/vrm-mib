<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LegacyClaimViewController;
use App\Http\Controllers\LegacySurveyViewController;
use App\Http\Controllers\SurveyReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dedicated Survey Condition Report (Printable & View 1:1 Legacy result.php)
Route::get('/surveys/{survey}/report', [SurveyReportController::class, 'show'])
    ->name('surveys.report');

// Dedicated Legacy 1:1 Jeramin Admin Views for Form & Result
Route::get('/admin/surveys/{survey}/form', [LegacySurveyViewController::class, 'showForm'])
    ->name('surveys.legacy.form');
Route::post('/admin/surveys/{survey}/form', [LegacySurveyViewController::class, 'updateForm'])
    ->name('surveys.legacy.form.update');
Route::get('/admin/surveys/{survey}/result', [LegacySurveyViewController::class, 'showResult'])
    ->name('surveys.legacy.result');

// Dedicated secure document streaming & missing file handler
Route::get('/document/attachment/{filename}', [DocumentController::class, 'show'])
    ->where('filename', '.*')
    ->name('document.attachment');

Route::get('/storage/attachment/{filename}', [DocumentController::class, 'show'])
    ->where('filename', '.*');

Route::get('/storage/attachments/{filename}', [DocumentController::class, 'show'])
    ->where('filename', '.*');

// Dedicated Legacy 1:1 Claim Solution Hub & Data Views
Route::get('/admin/claims', [LegacyClaimViewController::class, 'index'])
    ->name('claims.legacy.index');
Route::match(['get', 'post'], '/admin/claims/years', [LegacyClaimViewController::class, 'getYears'])
    ->name('claims.legacy.years');
Route::get('/admin/claims/data/{type?}/{year?}', [LegacyClaimViewController::class, 'data'])
    ->name('claims.legacy.data');
Route::get('/admin/claims/{claim}', [LegacyClaimViewController::class, 'showDetail'])
    ->whereNumber('claim')
    ->name('claims.legacy.detail');
Route::get('/admin/claims/{claim}/detail', [LegacyClaimViewController::class, 'showDetail'])
    ->whereNumber('claim');
Route::get('/admin/claims/{claim}/summary', [LegacyClaimViewController::class, 'showSummary'])
    ->whereNumber('claim')
    ->name('claims.legacy.summary');
