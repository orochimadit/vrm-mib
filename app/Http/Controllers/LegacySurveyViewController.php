<?php

namespace App\Http\Controllers;

use App\Models\InsuranceCompany;
use App\Models\Survey;
use App\Models\SurveyItem;
use App\Models\SurveyResponse;
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
}
