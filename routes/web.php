<?php

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\LegacyClaimViewController;
use App\Http\Controllers\LegacyDashboardController;
use App\Http\Controllers\LegacyInsuranceController;
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

// Dedicated Legacy 1:1 Insurance Company
Route::get('/admin/insurance-companies', [LegacyInsuranceController::class, 'indexCompanies'])
    ->name('insurance.companies.index');
Route::get('/admin/insurance-companies/create', [LegacyInsuranceController::class, 'createCompany'])
    ->name('insurance.companies.create');
Route::post('/admin/insurance-companies', [LegacyInsuranceController::class, 'storeCompany'])
    ->name('insurance.companies.store');
Route::get('/admin/insurance-companies/{company}/edit', [LegacyInsuranceController::class, 'editCompany'])
    ->name('insurance.companies.edit');
Route::match(['put', 'post'], '/admin/insurance-companies/{company}', [LegacyInsuranceController::class, 'updateCompany'])
    ->name('insurance.companies.update');
Route::match(['delete', 'get'], '/admin/insurance-companies/{company}/delete', [LegacyInsuranceController::class, 'deleteCompany'])
    ->name('insurance.companies.delete');

// Legacy Insurance Company Aliases
Route::get('/insurance/company', [LegacyInsuranceController::class, 'indexCompanies']);
Route::get('/insurance/company.html', [LegacyInsuranceController::class, 'indexCompanies']);
Route::get('/insurance/company/create', [LegacyInsuranceController::class, 'createCompany']);
Route::get('/insurance/company/create.html', [LegacyInsuranceController::class, 'createCompany']);
Route::get('/insurance/company/form', [LegacyInsuranceController::class, 'createCompany']);
Route::post('/insurance/company/proses', function (\Illuminate\Http\Request $request, LegacyInsuranceController $controller) {
    if ($request->input('id')) {
        return $controller->updateCompany($request, $request->input('id'));
    }
    return $controller->storeCompany($request);
});
Route::get('/insurance/company/edit/{company}', [LegacyInsuranceController::class, 'editCompany']);
Route::get('/insurance/company/delete/{company}', [LegacyInsuranceController::class, 'deleteCompany']);

// Dedicated Legacy 1:1 Product Insurance
Route::get('/admin/insurance-products', [LegacyInsuranceController::class, 'indexProducts'])
    ->name('insurance.products.index');
Route::get('/admin/insurance-products/company/{company?}', [LegacyInsuranceController::class, 'companyProducts'])
    ->name('insurance.products.company');
Route::get('/admin/insurance-products/create', [LegacyInsuranceController::class, 'createProduct'])
    ->name('insurance.products.create');
Route::post('/admin/insurance-products', [LegacyInsuranceController::class, 'storeProduct'])
    ->name('insurance.products.store');
Route::get('/admin/insurance-products/{product}/edit', [LegacyInsuranceController::class, 'editProduct'])
    ->name('insurance.products.edit');
Route::match(['put', 'post'], '/admin/insurance-products/{product}', [LegacyInsuranceController::class, 'updateProduct'])
    ->name('insurance.products.update');
Route::match(['delete', 'get'], '/admin/insurance-products/{product}/delete', [LegacyInsuranceController::class, 'deleteProduct'])
    ->name('insurance.products.delete');

// Legacy Insurance Product Aliases
Route::get('/insurance/product', [LegacyInsuranceController::class, 'indexProducts']);
Route::get('/insurance/product.html', [LegacyInsuranceController::class, 'indexProducts']);
Route::get('/insurance/product_claim', [LegacyInsuranceController::class, 'indexProducts']);
Route::get('/insurance/product/data/{company?}', [LegacyInsuranceController::class, 'companyProducts']);
Route::get('/insurance/product/data/{company?}.html', [LegacyInsuranceController::class, 'companyProducts']);
Route::get('/insurance/product/create', [LegacyInsuranceController::class, 'createProduct']);
Route::get('/insurance/product/create.html', [LegacyInsuranceController::class, 'createProduct']);
Route::post('/insurance/product/process', function (\Illuminate\Http\Request $request, LegacyInsuranceController $controller) {
    if ($request->input('id')) {
        return $controller->updateProduct($request, $request->input('id'));
    }
    return $controller->storeProduct($request);
});
Route::post('/insurance/product/proses', function (\Illuminate\Http\Request $request, LegacyInsuranceController $controller) {
    if ($request->input('id')) {
        return $controller->updateProduct($request, $request->input('id'));
    }
    return $controller->storeProduct($request);
});
Route::get('/insurance/product/edit/{product}', [LegacyInsuranceController::class, 'editProduct']);
Route::get('/insurance/product/delete/{product}', [LegacyInsuranceController::class, 'deleteProduct']);

// Attributes & Items
Route::get('/insurance/attributes/set/{product}', [LegacyInsuranceController::class, 'setAttributes'])
    ->name('insurance.attributes.set');
Route::get('/admin/insurance/attributes/{product}', [LegacyInsuranceController::class, 'setAttributes']);
Route::post('/insurance/attributes/save', [LegacyInsuranceController::class, 'saveAttribute']);
Route::get('/insurance/attributes/delete/{attribute}', [LegacyInsuranceController::class, 'deleteAttribute']);
Route::post('/insurance/attributes/pic/save', [LegacyInsuranceController::class, 'savePic']);
Route::get('/insurance/attributes/pic/delete/{pic}', [LegacyInsuranceController::class, 'deletePic']);

Route::get('/surveys/items/index/{product}', [LegacyInsuranceController::class, 'surveyItems'])
    ->name('surveys.items.index');
Route::get('/admin/surveys/items/{product}', [LegacyInsuranceController::class, 'surveyItems']);
Route::post('/surveys/items/proses', [LegacyInsuranceController::class, 'saveSurveyItem']);
Route::get('/surveys/items/get_items/{item}', [LegacyInsuranceController::class, 'getSurveyItem']);
Route::get('/surveys/items/delete/{item}', [LegacyInsuranceController::class, 'deleteSurveyItem']);
Route::get('/surveys/items/delete_items/{item}', [LegacyInsuranceController::class, 'deleteSurveyItem']);
