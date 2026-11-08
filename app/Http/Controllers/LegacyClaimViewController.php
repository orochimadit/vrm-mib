<?php

namespace App\Http\Controllers;

use App\Models\Claim;
use App\Models\Vessel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegacyClaimViewController extends Controller
{
    /**
     * Tampilkan Halaman Hub Claim Solution (3 Kartu Portofolio & Modal Tahun)
     * 1:1 dengan format legacy Jeramin (claims/views/index.php).
     */
    public function index(): View
    {
        $types = [
            'consultant' => 'Consultant',
            'portofolio' => 'Portofolio',
            'all' => 'All Portofolio',
        ];

        $counts = [
            'consultant' => Claim::where('portfolio_consultant', 'consultant')->count(),
            'portofolio' => Claim::where('portfolio_consultant', 'portofolio')->count(),
            'all' => Claim::count(),
        ];

        return view('legacy-admin.claims-hub', compact('types', 'counts'));
    }

    /**
     * Kembalikan daftar tahun yang memiliki kasus klaim untuk modal filter.
     */
    public function getYears(Request $request): JsonResponse
    {
        $portfolio = $request->input('portofolio', 'all');

        $query = Claim::whereNotNull('case_handled');

        if ($portfolio !== 'all') {
            $query->where('portfolio_consultant', $portfolio);
        }

        $years = $query->selectRaw("DISTINCT EXTRACT(YEAR FROM case_handled)::int as tahun")
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        array_unshift($years, 'All');

        return response()->json($years);
    }

    /**
     * Tampilkan Tabel Data Kasus Klaim terfilter berdasarkan Portfolio & Tahun
     * 1:1 dengan format legacy Jeramin (claims/views/data.php).
     */
    public function data(string $type = 'all', string $year = 'all'): View
    {
        $query = Claim::with([
            'vessel.ownerCompany',
            'insuranceCompany',
            'insuranceProduct',
            'casualtyRef',
        ]);

        if (strtolower($type) === 'consultant') {
            $query->where('portfolio_consultant', 'consultant');
        } elseif (strtolower($type) === 'portofolio') {
            $query->where('portfolio_consultant', 'portofolio');
        }

        if (strtolower($year) !== 'all' && is_numeric($year)) {
            $query->whereRaw("EXTRACT(YEAR FROM case_handled) = ?", [(int) $year]);
        }

        $claims = $query->orderBy('case_handled', 'desc')->get();

        $availableYears = Claim::whereNotNull('case_handled')
            ->when($type !== 'all', fn ($q) => $q->where('portfolio_consultant', $type))
            ->selectRaw("DISTINCT EXTRACT(YEAR FROM case_handled)::int as tahun")
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        return view('legacy-admin.claims-data', compact('claims', 'type', 'year', 'availableYears'));
    }

    /**
     * Tampilkan Halaman Detail Kasus Klaim 1:1 format legacy Jeramin (claims/views/detail.php).
     */
    public function showDetail(Claim $claim): View
    {
        $claim->loadMissing([
            'vessel.ownerCompany',
            'insuranceCompany',
            'insuranceProduct',
            'casualtyRef',
        ]);

        return view('legacy-admin.claims-detail', compact('claim'));
    }

    /**
     * Tampilkan Halaman Summary Kasus Klaim 1:1 format legacy Jeramin (claims/views/summary.php).
     */
    public function showSummary(Claim $claim): View
    {
        $claim->loadMissing([
            'vessel.ownerCompany',
            'insuranceCompany',
            'insuranceProduct',
            'casualtyRef',
        ]);

        return view('legacy-admin.claims-summary', compact('claim'));
    }
}
