<?php

namespace App\Http\Controllers;

use App\Models\Broker;
use App\Models\InsuranceCompany;
use App\Models\Survey;
use App\Models\SurveyDefect;
use App\Models\SurveyItem;
use App\Models\SurveyResponse;
use App\Models\Surveyor;
use App\Models\Vessel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LegacySurveyViewController extends Controller
{
    /**
     * Tampilkan Halaman Hub Services (Dua Tombol Besar: VRM & Claim Solution)
     * 1:1 dengan dashboard/services.html (dashboard/views/dashboard_index.php).
     */
    public function servicesHub(): View
    {
        return view('legacy-admin.services-hub');
    }

    /**
     * Tampilkan Halaman Hub Asuransi Vessel Risk Management (16 Kartu Perusahaan Asuransi + Modal Tahun)
     * 1:1 dengan format legacy Jeramin (surveys/views/index_data.php).
     */
    public function companiesHub(): View
    {
        // Ambil daftar perusahaan asuransi yang memiliki survei
        $companies = DB::table('surveys as s')
            ->join('insurance_companies as c', 's.insurance_company_id', '=', 'c.id')
            ->select('c.id', 'c.name', DB::raw('count(s.id) as total_surveys'))
            ->groupBy('c.id', 'c.name')
            ->orderBy('total_surveys', 'desc')
            ->get();

        $totalAllSurveys = Survey::count();

        return view('legacy-admin.surveys-hub', compact('companies', 'totalAllSurveys'));
    }

    /**
     * Kembalikan daftar tahun policy period untuk perusahaan asuransi terpilih (AJAX).
     */
    public function getYears(Request $request): JsonResponse
    {
        $companyId = (int) $request->input('company_id', 0);

        $query = Survey::whereNotNull('policy_period_start');

        if ($companyId > 0) {
            $query->where('insurance_company_id', $companyId);
        }

        $years = $query->selectRaw("DISTINCT EXTRACT(YEAR FROM policy_period_start)::int as tahun")
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Bersihkan nilai null jika ada
        $years = array_values(array_filter($years, fn ($y) => !is_null($y) && $y > 0));

        // Tambahkan ALL di akhir sesuai format legacy
        $years[] = 'ALL';

        return response()->json($years);
    }

    /**
     * Tampilkan Tabel Data Survei Kapal terfilter berdasarkan Perusahaan Asuransi, Tahun, dan Tab Treatment
     * 1:1 dengan format legacy Jeramin (surveys/views/data.php).
     */
    public function data(Request $request, $companyId = 0, $year = 'All'): View
    {
        $companyId = (int) $companyId;
        $company = $companyId > 0 ? InsuranceCompany::find($companyId) : null;

        $query = Survey::with([
            'vessel.ownerCompany',
            'insuranceCompany',
            'surveyor',
        ]);

        if ($companyId > 0) {
            $query->where('insurance_company_id', $companyId);
        }

        if (strtoupper($year) !== 'ALL' && is_numeric($year)) {
            $query->whereRaw("EXTRACT(YEAR FROM policy_period_start) = ?", [(int) $year]);
        }

        $allSurveys = $query->orderBy('policy_period_start', 'desc')->get();

        // Kelompokkan data per Risk Treatment (1:1 format legacy M_surveys::risk_treatment)
        $groupedSurveys = [
            'Self Assessment' => [],
            'Photo Album' => [],
            'Survey' => [],
            'Underwriting' => [],
        ];

        foreach ($allSurveys as $s) {
            $treatment = trim($s->condition_type ?? 'Survey');
            if (!isset($groupedSurveys[$treatment])) {
                $groupedSurveys[$treatment] = [];
            }
            $groupedSurveys[$treatment][] = $s;
        }

        // Tentukan tab aktif default
        $activeTab = $request->input('tab');
        if (!$activeTab) {
            // Jika ada data di Self Assessment, pilih Self Assessment. Jika tidak, pilih tab yang ada datanya
            if (count($groupedSurveys['Self Assessment']) > 0) {
                $activeTab = 'Self Assessment';
            } elseif (count($groupedSurveys['Survey']) > 0) {
                $activeTab = 'Survey';
            } elseif (count($groupedSurveys['Photo Album']) > 0) {
                $activeTab = 'Photo Album';
            } else {
                $activeTab = 'Self Assessment';
            }
        }

        // Ambil daftar tahun yang tersedia untuk filter bar atas
        $availableYears = Survey::whereNotNull('policy_period_start')
            ->when($companyId > 0, fn ($q) => $q->where('insurance_company_id', $companyId))
            ->selectRaw("DISTINCT EXTRACT(YEAR FROM policy_period_start)::int as tahun")
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->filter(fn ($y) => $y > 0)
            ->toArray();

        return view('legacy-admin.surveys-data', compact(
            'groupedSurveys',
            'allSurveys',
            'companyId',
            'company',
            'year',
            'activeTab',
            'availableYears'
        ));
    }

    /**
     * Tampilkan Halaman Form Survei Lapangan 1:1 format legacy Jeramin (views/form.php).
     */
    public function showForm(Survey $survey): View
    {
        $survey->loadMissing([
            'vessel.ownerCompany',
            'vessel.vesselType',
            'surveyor',
            'insuranceCompany',
            'responses.attachments',
        ]);

        // Muat daftar pertanyaan checklist
        $items = SurveyItem::where('insurance_product_id', $survey->insurance_product_id)->get();
        if ($items->isEmpty()) {
            $items = SurveyItem::orderBy('item_id', 'asc')->limit(30)->get();
        }

        // Muat respon yang sudah tersimpan
        $responses = $survey->responses->keyBy('item_id');

        return view('legacy-admin.survey-form', compact('survey', 'items', 'responses'));
    }

    /**
     * Simpan respon dan pembaruan data dari Form Survei.
     */
    public function updateForm(Request $request, Survey $survey): RedirectResponse
    {
        $responsesData = $request->input('responses', []);

        foreach ($responsesData as $itemId => $val) {
            if ($val !== null) {
                SurveyResponse::updateOrCreate(
                    [
                        'survey_id' => $survey->id,
                        'item_id' => $itemId,
                    ],
                    [
                        'response' => $val,
                    ]
                );
            }
        }

        if ($request->filled('imo_number') && $survey->vessel) {
            $survey->vessel->update(['imo_number' => $request->input('imo_number')]);
        }

        return redirect()->back()->with('success', 'Data formulir survei dan respon checklist berhasil disimpan.');
    }

    /**
     * Tampilkan Halaman Hasil Evaluasi & Assessment Result 1:1 format legacy Jeramin (views/result_sa.php).
     */
    public function showResult(Survey $survey): View
    {
        $survey->loadMissing([
            'vessel.ownerCompany',
            'vessel.vesselType',
            'surveyor',
            'insuranceCompany',
            'responses.attachments',
        ]);

        $items = SurveyItem::where('insurance_product_id', $survey->insurance_product_id)->get();
        if ($items->isEmpty()) {
            $items = SurveyItem::orderBy('item_id', 'asc')->limit(30)->get();
        }

        $responses = $survey->responses->keyBy('item_id');

        return view('legacy-admin.survey-result', compact('survey', 'items', 'responses'));
    }

    /**
     * Tampilkan Halaman Form Condition Survey 1:1 format legacy Jeramin (views/form_condition.php).
     */
    public function showCondition(Survey $survey): View
    {
        $survey->loadMissing([
            'vessel.ownerCompany',
            'vessel.vesselType',
            'surveyor',
            'broker',
            'insuranceCompany',
        ]);

        return view('legacy-admin.survey-condition', compact('survey'));
    }

    /**
     * Simpan pembaruan dari Form Condition Survey.
     */
    public function updateCondition(Request $request, Survey $survey): RedirectResponse
    {
        $survey->update([
            'surveyor_name' => $request->input('surveyor_name'),
            'date_of_survey' => $request->input('date_of_survey'),
            'location' => $request->input('location'),
            'policy_period_start' => $request->input('policy_period_start'),
            'policy_period_end' => $request->input('policy_period_end'),
            'due_date' => $request->input('due_date'),
        ]);

        if ($request->input('submit') === 'next') {
            return redirect()->route('surveys.legacy.form', $survey->id);
        }

        return redirect()->back()->with('success', 'Data Condition Survey berhasil diperbarui.');
    }

    /**
     * Tampilkan Halaman List of Deficiency 1:1 format legacy Jeramin (views/deficiency.php).
     */
    public function showDeficiency(Request $request, Survey $survey): View
    {
        $survey->loadMissing([
            'vessel.ownerCompany',
            'surveyor',
            'insuranceCompany',
        ]);

        $status = $request->input('status', '0');

        $defects = SurveyDefect::where('survey_id', $survey->id)
            ->when($status === '2', fn ($q) => $q->whereIn('status', ['Approve', 'Closed', 'Completed']))
            ->when($status === '0', fn ($q) => $q->where(fn ($sq) => $sq->whereNotIn('status', ['Approve', 'Closed', 'Completed'])->orWhereNull('status')))
            ->orderBy('id', 'asc')
            ->get();

        return view('legacy-admin.survey-deficiency', compact('survey', 'defects', 'status'));
    }

    /**
     * Tampilkan Form Penugasan Surveyor (Edit) 1:1 format legacy Jeramin (views/form_penugasan.php).
     */
    public function showEdit(Survey $survey): View
    {
        $survey->loadMissing([
            'vessel.ownerCompany',
            'vessel.vesselType',
            'surveyor',
            'broker',
            'insuranceCompany',
        ]);

        $vessels = Vessel::with('ownerCompany', 'vesselType')->orderBy('name', 'asc')->get();
        $surveyors = Surveyor::orderBy('name', 'asc')->get();
        $brokers = Broker::orderBy('name', 'asc')->get();
        $insuranceCompanies = InsuranceCompany::orderBy('name', 'asc')->get();

        return view('legacy-admin.survey-edit', compact(
            'survey',
            'vessels',
            'surveyors',
            'brokers',
            'insuranceCompanies'
        ));
    }

    /**
     * Simpan pembaruan dari Form Penugasan (Edit).
     */
    public function updateEdit(Request $request, Survey $survey): RedirectResponse
    {
        $survey->update([
            'vessel_id' => $request->input('vessel_id', $survey->vessel_id),
            'surveyor_id' => $request->input('surveyor_id', $survey->surveyor_id),
            'broker_id' => $request->input('broker_id', $survey->broker_id),
            'insurance_company_id' => $request->input('insurance_company_id', $survey->insurance_company_id),
            'condition_type' => $request->input('condition_type', $survey->condition_type),
            'due_date' => $request->input('due_date', $survey->due_date),
            'policy_period_start' => $request->input('policy_period_start', $survey->policy_period_start),
            'policy_period_end' => $request->input('policy_period_end', $survey->policy_period_end),
        ]);

        return redirect()->back()->with('success', 'Data Penugasan Survei berhasil diperbarui.');
    }

    /**
     * Tampilkan Halaman Penugasan Survei Baru (Add New Assignment)
     * 1:1 format legacy kapal/views/form_penugasan.php (Screenshot vrm_surveys_add_new_assignment.png).
     */
    public function createAssignment(): View
    {
        $vessels = Vessel::with(['ownerCompany', 'vesselType'])->orderBy('name', 'asc')->get();
        $surveyors = Surveyor::orderBy('name', 'asc')->get();
        $brokers = Broker::orderBy('name', 'asc')->get();
        $insuranceCompanies = InsuranceCompany::orderBy('name', 'asc')->get();

        return view('legacy-admin.survey-assignment-create', compact(
            'vessels',
            'surveyors',
            'brokers',
            'insuranceCompanies'
        ));
    }

    /**
     * Simpan Penugasan Survei Baru ke Database.
     */
    public function storeAssignment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'vessel_id' => 'required|integer',
            'insurance_company_id' => 'required|integer',
            'surveyor_id' => 'nullable|integer',
            'broker_id' => 'nullable|integer',
            'condition_type' => 'required|string',
            'due_date' => 'nullable|date',
            'policy_period_start' => 'nullable|date',
            'policy_period_end' => 'nullable|date',
        ]);

        $surveyor = $request->filled('surveyor_id') ? Surveyor::find($request->surveyor_id) : null;
        $validated['surveyor_name'] = $surveyor?->name ?? 'PT. MARINE INSURANCE BROKER';
        $validated['status'] = 'Outstanding';
        $validated['form_status'] = 'draft';

        $survey = Survey::create($validated);

        $year = $survey->policy_period_start ? \Carbon\Carbon::parse($survey->policy_period_start)->format('Y') : 'ALL';

        return redirect()->route('surveys.legacy.data', [$survey->insurance_company_id, $year])
            ->with('success', 'Penugasan survei kapal berhasil ditambahkan.');
    }

    /**
     * API JSON detail kapal untuk AJAX penugasan.
     */
    public function apiVesselDetails($id): JsonResponse
    {
        $vessel = Vessel::with(['ownerCompany', 'vesselType'])->find($id);
        if (!$vessel) {
            return response()->json([], 404);
        }

        return response()->json([
            'company_name' => $vessel->ownerCompany?->name ?? '-',
            'contact_info' => $vessel->ownerCompany?->contact_info ?? '-',
            'pic' => $vessel->ownerCompany?->pic ?? '-',
            'name' => $vessel->name,
            'type' => $vessel->vesselType?->name ?? ($vessel->type_name_legacy ?? 'GENERAL CARGO'),
            'year_built' => $vessel->year_built ?? '-',
            'tsi' => number_format((float) $vessel->tsi, 2),
        ]);
    }
}
