<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LegacyClaimViewController;
use App\Http\Controllers\LegacyDashboardController;
use App\Http\Controllers\LegacySurveyViewController;
use App\Http\Controllers\LegacyVesselCompanyController;
use App\Http\Controllers\SurveyReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dedicated Legacy 1:1 Dashboard & Modern Analytics
Route::get('/admin/dashboard', [LegacyDashboardController::class, 'index'])
    ->name('dashboard.legacy.index');
Route::get('/dashboard', [LegacyDashboardController::class, 'index']);

// Dedicated Survey Condition Report (Printable & View 1:1 Legacy result.php)
Route::get('/surveys/{survey}/report', [SurveyReportController::class, 'show'])
    ->name('surveys.report');

// Dedicated Legacy 1:1 Jeramin Admin Views for Form & Result
Route::get('/admin/services', [LegacySurveyViewController::class, 'servicesHub'])
    ->name('services.legacy.hub');
Route::get('/admin/surveys', [LegacySurveyViewController::class, 'companiesHub'])
    ->name('surveys.legacy.hub');
Route::get('/admin/surveys/create', [LegacySurveyViewController::class, 'createAssignment'])
    ->name('surveys.legacy.create');
Route::post('/admin/surveys', [LegacySurveyViewController::class, 'storeAssignment'])
    ->name('surveys.legacy.store');
Route::get('/admin/surveys/api/vessel/{id}', [LegacySurveyViewController::class, 'apiVesselDetails'])
    ->name('surveys.legacy.api.vessel');
Route::get('/surveys/form_penugasan', [LegacySurveyViewController::class, 'createAssignment']);
Route::match(['get', 'post'], '/admin/surveys/years', [LegacySurveyViewController::class, 'getYears'])
    ->name('surveys.legacy.years');
Route::get('/admin/surveys/data/{companyId?}/{year?}', [LegacySurveyViewController::class, 'data'])
    ->name('surveys.legacy.data');
Route::get('/admin/surveys/{survey}/condition', [LegacySurveyViewController::class, 'showCondition'])
    ->name('surveys.legacy.condition');
Route::post('/admin/surveys/{survey}/condition', [LegacySurveyViewController::class, 'updateCondition'])
    ->name('surveys.legacy.condition.update');
Route::get('/admin/surveys/{survey}/form', [LegacySurveyViewController::class, 'showForm'])
    ->name('surveys.legacy.form');
Route::post('/admin/surveys/{survey}/form', [LegacySurveyViewController::class, 'updateForm'])
    ->name('surveys.legacy.form.update');
Route::get('/admin/surveys/{survey}/result', [LegacySurveyViewController::class, 'showResult'])
    ->name('surveys.legacy.result');
Route::get('/admin/surveys/{survey}/deficiency-list', [LegacySurveyViewController::class, 'showDeficiency'])
    ->name('surveys.legacy.deficiency');
Route::get('/admin/surveys/{survey}/assignment', [LegacySurveyViewController::class, 'showEdit'])
    ->name('surveys.legacy.edit');
Route::post('/admin/surveys/{survey}/assignment', [LegacySurveyViewController::class, 'updateEdit'])
    ->name('surveys.legacy.edit.update');

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

// Dedicated Legacy 1:1 Vessel & Owner Company
Route::get('/admin/companies', [LegacyVesselCompanyController::class, 'indexCompanies'])
    ->name('companies.legacy.index');
Route::get('/admin/companies/create', [LegacyVesselCompanyController::class, 'createCompany'])
    ->name('companies.legacy.create');
Route::post('/admin/companies', [LegacyVesselCompanyController::class, 'storeCompany'])
    ->name('companies.legacy.store');
Route::get('/admin/companies/{company}/edit', [LegacyVesselCompanyController::class, 'editCompany'])
    ->name('companies.legacy.edit');
Route::match(['put', 'post'], '/admin/companies/{company}', [LegacyVesselCompanyController::class, 'updateCompany'])
    ->name('companies.legacy.update');
Route::match(['delete', 'get'], '/admin/companies/{company}/delete', [LegacyVesselCompanyController::class, 'deleteCompany'])
    ->name('companies.legacy.delete');

// Dedicated Legacy 1:1 Vessels by Company & Management Form
Route::get('/admin/companies/{company}/vessels', [LegacyVesselCompanyController::class, 'companyVessels'])
    ->name('companies.legacy.vessels');
Route::get('/admin/vessels', [LegacyVesselCompanyController::class, 'companyVessels'])
    ->name('vessels.legacy.index');
Route::get('/admin/vessels/create', [LegacyVesselCompanyController::class, 'createVessel'])
    ->name('vessels.legacy.create');
Route::post('/admin/vessels', [LegacyVesselCompanyController::class, 'storeVessel'])
    ->name('vessels.legacy.store');
Route::get('/admin/vessels/{vessel}/edit', [LegacyVesselCompanyController::class, 'editVessel'])
    ->name('vessels.legacy.edit');
Route::match(['put', 'post'], '/admin/vessels/{vessel}', [LegacyVesselCompanyController::class, 'updateVessel'])
    ->name('vessels.legacy.update');
Route::match(['delete', 'get'], '/admin/vessels/{vessel}/delete', [LegacyVesselCompanyController::class, 'deleteVessel'])
    ->name('vessels.legacy.delete');

// Aliases matching exact legacy URLs
Route::get('/kapal/company', [LegacyVesselCompanyController::class, 'indexCompanies']);
Route::get('/kapal/company.html', [LegacyVesselCompanyController::class, 'indexCompanies']);
Route::get('/kapal/company/form', [LegacyVesselCompanyController::class, 'createCompany']);
Route::get('/kapal/company/form.html', [LegacyVesselCompanyController::class, 'createCompany']);
Route::get('/kapal/index/{company}', [LegacyVesselCompanyController::class, 'companyVessels']);
Route::get('/kapal/index/{company}.html', [LegacyVesselCompanyController::class, 'companyVessels']);
Route::get('/kapal/form', [LegacyVesselCompanyController::class, 'createVessel']);
Route::get('/kapal/form.html', [LegacyVesselCompanyController::class, 'createVessel']);
