<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use App\Models\InsuranceProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LegacyInsuranceController extends Controller
{
    public const RISK_TREATMENTS = [
        1 => 'Self Assessment',
        2 => 'Photo Album',
        3 => 'Survey',
        4 => 'Underwriting',
    ];

    /* =========================================================================
     * INSURANCE COMPANY
     * ========================================================================= */

    /**
     * List all Insurance Companies (1:1 with legacy /insurance/company.html)
     */
    public function indexCompanies(Request $request)
    {
        $companies = InsuranceCompany::orderBy('name', 'asc')->get();

        return view('legacy-admin.insurance.company-index', [
            'title' => 'Insurance Company',
            'companies' => $companies,
        ]);
    }

    /**
     * Create Insurance Company Form (1:1 with legacy /insurance/company/create.html)
     */
    public function createCompany(Request $request)
    {
        return view('legacy-admin.insurance.company-form', [
            'title' => 'Create Insurance Company',
            'company' => null,
            'isEdit' => false,
        ]);
    }

    /**
     * Store Insurance Company
     */
    public function storeCompany(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
        ]);

        InsuranceCompany::create($validated);

        return redirect('/admin/insurance-companies')
            ->with('msg', '<div class="alert alert-success">Company data has been added successfully</div>');
    }

    /**
     * Edit Insurance Company Form
     */
    public function editCompany($id)
    {
        $company = InsuranceCompany::findOrFail($id);

        return view('legacy-admin.insurance.company-form', [
            'title' => 'Edit Insurance Company',
            'company' => $company,
            'isEdit' => true,
        ]);
    }

    /**
     * Update Insurance Company
     */
    public function updateCompany(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id');
        $company = InsuranceCompany::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string|max:255',
            'email' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
        ]);

        $company->update($validated);

        return redirect('/admin/insurance-companies')
            ->with('msg', '<div class="alert alert-success">Company data has been updated successfully</div>');
    }

    /**
     * Delete Insurance Company
     */
    public function deleteCompany($id)
    {
        $company = InsuranceCompany::findOrFail($id);
        $company->delete();

        return redirect('/admin/insurance-companies')
            ->with('msg', '<div class="alert alert-success">Company data has been deleted successfully</div>');
    }

    /* =========================================================================
     * PRODUCT INSURANCE
     * ========================================================================= */

    /**
     * Company Cards Grid Hub (1:1 with legacy /insurance/product.html)
     */
    public function indexProducts(Request $request)
    {
        $companies = InsuranceCompany::orderBy('name', 'asc')->get();

        return view('legacy-admin.insurance.product-hub', [
            'title' => 'Product Management',
            'subtitle' => 'Product Insurance',
            'companies' => $companies,
        ]);
    }

    /**
     * Product Management Table for a specific Company (1:1 with legacy /insurance/product/data/{id})
     */
    public function companyProducts(Request $request, $companyId = 0)
    {
        $company = null;
        $query = InsuranceProduct::with('insuranceCompany');

        if ($companyId > 0) {
            $company = InsuranceCompany::findOrFail($companyId);
            $query->where('insurance_company_id', $companyId);
        }

        $products = $query->orderBy('name', 'asc')->get();

        return view('legacy-admin.insurance.product-table', [
            'title' => 'Product Management',
            'subtitle' => 'Product Insurance',
            'company' => $company,
            'companyId' => $companyId,
            'products' => $products,
            'riskTreatments' => self::RISK_TREATMENTS,
        ]);
    }

    /**
     * Create Product Form (1:1 with legacy /insurance/product/create)
     */
    public function createProduct(Request $request)
    {
        $companies = InsuranceCompany::orderBy('name', 'asc')->get();
        $selectedCompanyId = $request->query('company_id');

        return view('legacy-admin.insurance.product-form', [
            'title' => 'Create Product',
            'product' => null,
            'companies' => $companies,
            'selectedCompanyId' => $selectedCompanyId,
            'riskTreatments' => self::RISK_TREATMENTS,
            'isEdit' => false,
        ]);
    }

    /**
     * Store Product
     */
    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'company_id' => 'required|exists:insurance_companies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'risk_treatment' => 'nullable|integer',
            'risk_treatment_label' => 'nullable|string|max:255',
        ]);

        $product = InsuranceProduct::create([
            'insurance_company_id' => $validated['company_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'risk_treatment' => $validated['risk_treatment'] ?? null,
            'risk_treatment_label' => $validated['risk_treatment_label'] ?? null,
            'price' => 0,
            'machinery_particulars' => 0,
            'additional_machinery_spaces' => 0,
            'vessel_pic' => 0,
        ]);

        return redirect('/admin/insurance-products/company/' . $product->insurance_company_id)
            ->with('msg', '<div class="alert alert-success">Product has been added successfully.</div>');
    }

    /**
     * Edit Product Form
     */
    public function editProduct($id)
    {
        $product = InsuranceProduct::with('insuranceCompany')->findOrFail($id);
        $companies = InsuranceCompany::orderBy('name', 'asc')->get();

        return view('legacy-admin.insurance.product-form', [
            'title' => 'Edit Product',
            'product' => $product,
            'companies' => $companies,
            'selectedCompanyId' => $product->insurance_company_id,
            'riskTreatments' => self::RISK_TREATMENTS,
            'isEdit' => true,
        ]);
    }

    /**
     * Update Product
     */
    public function updateProduct(Request $request, $id = null)
    {
        $id = $id ?: $request->input('id');
        $product = InsuranceProduct::findOrFail($id);

        $validated = $request->validate([
            'company_id' => 'required|exists:insurance_companies,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'risk_treatment' => 'nullable|integer',
            'risk_treatment_label' => 'nullable|string|max:255',
        ]);

        $product->update([
            'insurance_company_id' => $validated['company_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'risk_treatment' => $validated['risk_treatment'] ?? null,
            'risk_treatment_label' => $validated['risk_treatment_label'] ?? null,
        ]);

        return redirect('/admin/insurance-products/company/' . $product->insurance_company_id)
            ->with('msg', '<div class="alert alert-success">Product has been updated successfully.</div>');
    }

    /**
     * Delete Product
     */
    public function deleteProduct($id)
    {
        $product = InsuranceProduct::findOrFail($id);
        $companyId = $product->insurance_company_id;
        $product->delete();

        return redirect('/admin/insurance-products/company/' . $companyId)
            ->with('msg', '<div class="alert alert-success">Product has been deleted successfully.</div>');
    }

    /**
     * Vessel Attributes Configuration View (1:1 with /insurance/attributes/set/{id})
     */
    public function setAttributes($id)
    {
        $product = InsuranceProduct::with('insuranceCompany')->findOrFail($id);

        return view('legacy-admin.insurance.attributes-set', [
            'title' => 'Set Vessel Attributes',
            'product' => $product,
            'riskTreatments' => self::RISK_TREATMENTS,
        ]);
    }

    /**
     * Survey Items View (1:1 with /surveys/items/index/{id})
     */
    public function surveyItems($id)
    {
        $product = InsuranceProduct::with('insuranceCompany')->findOrFail($id);
        $items = DB::table('survey_items')->where('insurance_product_id', $id)->orderBy('id')->get();

        return view('legacy-admin.insurance.survey-items', [
            'title' => 'Survey Items Checklist',
            'product' => $product,
            'items' => $items,
        ]);
    }
}
