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

    public const RISK_VALUES = [
        1 => 'Low',
        2 => 'Medium',
        3 => 'High',
        4 => 'Critical',
        'LOW' => 'Low',
        'MEDIUM' => 'Medium',
        'HIGH' => 'High',
        'CRITICAL' => 'Critical',
    ];

    /* =========================================================================
     * INSURANCE COMPANY
     * ========================================================================= */

    public function indexCompanies(Request $request)
    {
        $companies = InsuranceCompany::orderBy('name', 'asc')->get();

        return view('legacy-admin.insurance.company-index', [
            'title' => 'Insurance Company',
            'companies' => $companies,
        ]);
    }

    public function createCompany(Request $request)
    {
        return view('legacy-admin.insurance.company-form', [
            'title' => 'Create Insurance Company',
            'company' => null,
            'isEdit' => false,
        ]);
    }

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

    public function editCompany($id)
    {
        $company = InsuranceCompany::findOrFail($id);

        return view('legacy-admin.insurance.company-form', [
            'title' => 'Edit Insurance Company',
            'company' => $company,
            'isEdit' => true,
        ]);
    }

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

    public function indexProducts(Request $request)
    {
        $companies = InsuranceCompany::orderBy('name', 'asc')->get();

        return view('legacy-admin.insurance.product-hub', [
            'title' => 'Product Management',
            'subtitle' => 'Product Insurance',
            'companies' => $companies,
        ]);
    }

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

    public function deleteProduct($id)
    {
        $product = InsuranceProduct::findOrFail($id);
        $companyId = $product->insurance_company_id;
        $product->delete();

        return redirect('/admin/insurance-products/company/' . $companyId)
            ->with('msg', '<div class="alert alert-success">Product has been deleted successfully.</div>');
    }

    /* =========================================================================
     * VESSEL ATTRIBUTES (1:1 with legacy /insurance/attributes/set/{id})
     * ========================================================================= */

    public function setAttributes($id)
    {
        $product = InsuranceProduct::with('insuranceCompany')->findOrFail($id);

        $baseParticulars = [
            ['label' => 'Vessel Name', 'editable' => false],
            ['label' => 'Vessel Type', 'editable' => false],
            ['label' => 'Year Built', 'editable' => false],
            ['label' => 'GRT', 'editable' => false],
            ['label' => 'Total Sum Insured', 'editable' => false],
        ];

        $customParticulars = DB::table('vessel_attributes')
            ->where('insurance_product_id', $id)
            ->where('category_attr_type', 'vessel_particulars')
            ->orderBy('attribute_id')
            ->get();

        $mainEngine = DB::table('vessel_attributes')
            ->where('insurance_product_id', $id)
            ->where('category_attr_type', 'main_engine')
            ->orderBy('attribute_id')
            ->get();

        $auxEngine = DB::table('vessel_attributes')
            ->where('insurance_product_id', $id)
            ->where('category_attr_type', 'auxiliary_engine')
            ->orderBy('attribute_id')
            ->get();

        $addMachinery = DB::table('vessel_attributes')
            ->where('insurance_product_id', $id)
            ->where('category_attr_type', 'additional_machinery_spaces')
            ->orderBy('attribute_id')
            ->get();

        $picList = DB::table('vessel_pic')
            ->where('insurance_product_id', $id)
            ->orderBy('id_pic')
            ->get();

        if ($picList->isEmpty()) {
            foreach (['Master', 'Chief Officer', 'Chief Engineer', 'DPA'] as $pos) {
                DB::table('vessel_pic')->insert([
                    'insurance_product_id' => $id,
                    'position' => $pos,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $picList = DB::table('vessel_pic')->where('insurance_product_id', $id)->orderBy('id_pic')->get();
        }

        return view('legacy-admin.insurance.attributes-set', [
            'title' => 'Set Vessel Attributes',
            'product' => $product,
            'riskTreatments' => self::RISK_TREATMENTS,
            'baseParticulars' => $baseParticulars,
            'customParticulars' => $customParticulars,
            'mainEngine' => $mainEngine,
            'auxEngine' => $auxEngine,
            'addMachinery' => $addMachinery,
            'picList' => $picList,
        ]);
    }

    public function saveAttribute(Request $request)
    {
        $insuranceProductId = $request->input('insurance_product_id');
        $attributeId = $request->input('attribute_id');
        $label = $request->input('label');
        $categoryAttr = $request->input('category_attr', 'General Particulars');
        $categoryAttrType = $request->input('category_attr_type', 'vessel_particulars');

        if ($attributeId) {
            DB::table('vessel_attributes')
                ->where('attribute_id', $attributeId)
                ->update([
                    'label' => $label,
                    'updated_at' => now(),
                ]);
            $msg = 'Attribute updated successfully';
        } else {
            DB::table('vessel_attributes')->insert([
                'insurance_product_id' => $insuranceProductId,
                'category_attr_type' => $categoryAttrType,
                'category_attr' => $categoryAttr,
                'label' => $label,
                'type' => 'text',
                'option_id' => 0,
                'parent' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $msg = 'Attribute added successfully';
        }

        if ($request->ajax()) {
            return response()->json(['status' => true, 'message' => $msg]);
        }

        return redirect()->back()->with('msg', '<div class="alert alert-success">' . $msg . '</div>');
    }

    public function deleteAttribute(Request $request, $attributeId)
    {
        DB::table('vessel_attributes')->where('attribute_id', $attributeId)->delete();

        if ($request->ajax()) {
            return response()->json(['status' => true, 'message' => 'Attribute deleted']);
        }

        return redirect()->back()->with('msg', '<div class="alert alert-success">Attribute deleted successfully</div>');
    }

    public function savePic(Request $request)
    {
        $insuranceProductId = $request->input('insurance_product_id');
        $idPic = $request->input('id_pic');
        $position = $request->input('position');

        if ($idPic) {
            DB::table('vessel_pic')->where('id_pic', $idPic)->update([
                'position' => $position,
                'updated_at' => now(),
            ]);
            $msg = 'Position updated successfully';
        } else {
            DB::table('vessel_pic')->insert([
                'insurance_product_id' => $insuranceProductId,
                'position' => $position,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $msg = 'Position added successfully';
        }

        if ($request->ajax()) {
            return response()->json(['status' => true, 'message' => $msg]);
        }

        return redirect()->back()->with('msg', '<div class="alert alert-success">' . $msg . '</div>');
    }

    public function deletePic(Request $request, $idPic)
    {
        DB::table('vessel_pic')->where('id_pic', $idPic)->delete();

        if ($request->ajax()) {
            return response()->json(['status' => true, 'message' => 'Position deleted']);
        }

        return redirect()->back()->with('msg', '<div class="alert alert-success">Position deleted successfully</div>');
    }

    /* =========================================================================
     * SURVEY ITEMS (1:1 with legacy /surveys/items/index/{id})
     * ========================================================================= */

    public function surveyItems(Request $request, $id)
    {
        $product = InsuranceProduct::with('insuranceCompany')->findOrFail($id);

        $items = DB::table('survey_items')
            ->where('insurance_product_id', $id)
            ->orderBy('item_id', 'asc')
            ->get();

        return view('legacy-admin.insurance.survey-items', [
            'title' => 'Survey Items List',
            'product' => $product,
            'items' => $items,
            'riskValueExpected' => self::RISK_VALUES,
            'riskTreatments' => self::RISK_TREATMENTS,
        ]);
    }

    public function saveSurveyItem(Request $request)
    {
        $itemId = $request->input('item_id');
        $insuranceProductId = $request->input('insurance_product_id');
        $refNo = $request->input('ref_no');
        $category = $request->input('category');
        $itemName = $request->input('item_name');
        $riskValueExpected = $request->input('risk_value_expected');

        $data = [
            'insurance_product_id' => $insuranceProductId,
            'ref_no' => $refNo,
            'category' => $category,
            'item_name' => $itemName,
            'risk_value' => is_numeric($riskValueExpected) ? $riskValueExpected : 1,
            'risk_value_expected' => self::RISK_VALUES[$riskValueExpected] ?? 'Low',
            'updated_at' => now(),
        ];

        if ($itemId) {
            DB::table('survey_items')->where('item_id', $itemId)->update($data);
            $msg = 'Survey item updated successfully';
        } else {
            $data['created_at'] = now();
            DB::table('survey_items')->insert($data);
            $msg = 'Survey item added successfully';
        }

        return redirect('/surveys/items/index/' . $insuranceProductId)
            ->with('msg', '<div class="alert alert-success">' . $msg . '</div>');
    }

    public function getSurveyItem($itemId)
    {
        $item = DB::table('survey_items')->where('item_id', $itemId)->first();

        return response()->json($item);
    }

    public function deleteSurveyItem($itemId)
    {
        $item = DB::table('survey_items')->where('item_id', $itemId)->first();
        $insuranceProductId = $item ? $item->insurance_product_id : 1;

        DB::table('survey_items')->where('item_id', $itemId)->delete();

        return redirect('/surveys/items/index/' . $insuranceProductId)
            ->with('msg', '<div class="alert alert-success">Survey item deleted successfully</div>');
    }
}
