<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Company;
use App\Models\InsuranceCompany;
use App\Models\Survey;
use App\Models\Vessel;
use App\Models\VesselType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LegacyDashboardController extends Controller
{
    /**
     * Tampilkan Halaman Utama Dashboard Visualisasi Data
     * Menggabungkan visualisasi 1:1 "Total Policy by Years" (Highcharts Dark Theme)
     * ditambah visualisasi eksekutif rekomendasi (Insurance Market Share, Risk Treatment, Status, TSI, GRT, Claim Cases).
     */
    public function index(Request $request): View
    {
        $selectedYear = $request->input('year', 'ALL');

        // Query dasar survei
        $surveyQuery = Survey::query();
        if ($selectedYear !== 'ALL' && is_numeric($selectedYear)) {
            $surveyQuery->whereRaw("EXTRACT(YEAR FROM policy_period_start) = ?", [(int) $selectedYear]);
        }

        // 1. KPI Top Summary Cards
        $totalSurveys = (clone $surveyQuery)->count();
        $completedSurveys = (clone $surveyQuery)->whereIn(DB::raw('LOWER(status)'), ['completed', 'selesai', 'approved'])->count();
        $outstandingSurveys = (clone $surveyQuery)->where(function ($q) {
            $q->whereIn(DB::raw('LOWER(status)'), ['outstanding', 'open', 'draft'])
              ->orWhereNull('status');
        })->count();

        $completionRate = $totalSurveys > 0 ? round(($completedSurveys / $totalSurveys) * 100, 1) : 0;

        $totalVessels = Vessel::count();
        $totalTsi = Vessel::sum('tsi') ?? 0;
        $totalGrt = Vessel::sum('grt') ?? 0;
        $totalClaims = Claim::count();
        $ongoingClaims = Claim::where(DB::raw('LOWER(status)'), 'ongoing')->count();
        $acceptedClaims = Claim::where(DB::raw('LOWER(status)'), 'accepted')->count();

        // 2. Data Chart 1: "Total Policy by Years" (1:1 sesuai screenshot legacy Highcharts dark theme)
        $rawYearData = DB::table('surveys')
            ->selectRaw("COALESCE(EXTRACT(YEAR FROM policy_period_start)::int, 0) as yr, count(*) as cnt")
            ->groupBy('yr')
            ->orderBy('yr', 'asc')
            ->get();

        $chartYears = [];
        $chartYearCounts = [];
        foreach ($rawYearData as $row) {
            $label = (string) ($row->yr === 0 ? '0' : $row->yr);
            $chartYears[] = $label;
            $chartYearCounts[] = (int) $row->cnt;
        }

        // 3. Data Chart 2: "Number of Vessels Based on Insurance Company" (Top 10)
        $rawInsuranceData = DB::table('surveys')
            ->join('insurance_companies', 'surveys.insurance_company_id', '=', 'insurance_companies.id')
            ->when($selectedYear !== 'ALL' && is_numeric($selectedYear), function ($q) use ($selectedYear) {
                $q->whereRaw("EXTRACT(YEAR FROM surveys.policy_period_start) = ?", [(int) $selectedYear]);
            })
            ->selectRaw('insurance_companies.name, count(*) as cnt')
            ->groupBy('insurance_companies.name')
            ->orderByDesc('cnt')
            ->limit(8)
            ->get();

        $insuranceLabels = [];
        $insuranceCounts = [];
        foreach ($rawInsuranceData as $row) {
            $insuranceLabels[] = $row->name;
            $insuranceCounts[] = (int) $row->cnt;
        }

        // 4. Data Chart 3: "Risk Treatment Distribution" (Donut Chart)
        $rawTreatmentData = DB::table('surveys')
            ->when($selectedYear !== 'ALL' && is_numeric($selectedYear), function ($q) use ($selectedYear) {
                $q->whereRaw("EXTRACT(YEAR FROM policy_period_start) = ?", [(int) $selectedYear]);
            })
            ->selectRaw("COALESCE(condition_type, 'Survey') as treatment, count(*) as cnt")
            ->groupBy('treatment')
            ->orderByDesc('cnt')
            ->get();

        $treatmentSeries = [];
        foreach ($rawTreatmentData as $row) {
            $treatmentSeries[] = [
                'name' => $row->treatment,
                'y' => (int) $row->cnt
            ];
        }

        // 5. Data Chart 4: "Survey Status Distribution"
        $statusSeries = [
            ['name' => 'Completed', 'y' => $completedSurveys, 'color' => '#28a745'],
            ['name' => 'Outstanding', 'y' => $outstandingSurveys, 'color' => '#dc3545'],
        ];

        // 6. Data Chart 5: "Claim Solutions by Status & Portfolio"
        $claimStatusData = DB::table('claims')
            ->selectRaw("COALESCE(status, 'ongoing') as st, count(*) as cnt")
            ->groupBy('st')
            ->pluck('cnt', 'st')
            ->toArray();

        // 7. Data Tabel Survei Terkini (5 Terbaru)
        $recentSurveys = Survey::with(['vessel.ownerCompany', 'insuranceCompany'])
            ->orderBy('policy_period_start', 'desc')
            ->limit(5)
            ->get();

        // Daftar tahun untuk filter tombol di atas
        $availableYears = DB::table('surveys')
            ->selectRaw("DISTINCT EXTRACT(YEAR FROM policy_period_start)::int as yr")
            ->whereNotNull('policy_period_start')
            ->orderBy('yr', 'desc')
            ->pluck('yr')
            ->filter(fn ($y) => $y > 0)
            ->toArray();

        return view('legacy-admin.dashboard-analytics', compact(
            'selectedYear',
            'availableYears',
            'totalSurveys',
            'completedSurveys',
            'outstandingSurveys',
            'completionRate',
            'totalVessels',
            'totalTsi',
            'totalGrt',
            'totalClaims',
            'ongoingClaims',
            'acceptedClaims',
            'chartYears',
            'chartYearCounts',
            'insuranceLabels',
            'insuranceCounts',
            'treatmentSeries',
            'statusSeries',
            'claimStatusData',
            'recentSurveys'
        ));
    }
}
