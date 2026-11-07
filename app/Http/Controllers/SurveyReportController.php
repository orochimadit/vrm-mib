<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SurveyReportController extends Controller
{
    /**
     * Tampilkan Condition Survey Report lengkap sesuai format legacy (views/result.php).
     */
    public function show(Survey $survey): View
    {
        $survey->loadMissing([
            'vessel.ownerCompany',
            'surveyor',
            'broker',
            'insuranceCompany',
            'defects.files',
            'responses.item',
            'responses.attachments',
        ]);

        // Hitung pengelompokan item survei dan skor per kategori
        $groupedResponses = $survey->responses->groupBy(function ($response) {
            return $response->item?->category ?: 'General Inspection';
        });

        $categoryResults = [];
        $grandTotalScore = 0;
        $grandTotalMinimum = 0;

        foreach ($groupedResponses as $category => $responses) {
            $catScore = 0;
            $catMinimum = 0;

            foreach ($responses as $resp) {
                $riskVal = (int) ($resp->item?->risk_value ?: 10);
                $respScore = is_numeric($resp->response) ? (int) $resp->response : 3;
                
                $itemTotal = $respScore * $riskVal;
                $itemMin = $riskVal * 2; // minimum standar baseline

                $catScore += $itemTotal;
                $catMinimum += $itemMin;
            }

            $catGrade = $catScore < $catMinimum ? 'C' : ($catScore == $catMinimum ? 'B' : 'A');

            $categoryResults[] = (object) [
                'category' => $category,
                'total_score' => $catScore,
                'minimum' => $catMinimum,
                'grade' => $catGrade,
                'items_count' => $responses->count(),
            ];

            $grandTotalScore += $catScore;
            $grandTotalMinimum += $catMinimum;
        }

        $overallGrade = $grandTotalScore < $grandTotalMinimum ? 'C' : ($grandTotalScore == $grandTotalMinimum ? 'B' : 'A');

        return view('reports.survey-condition-report', compact(
            'survey',
            'groupedResponses',
            'categoryResults',
            'grandTotalScore',
            'grandTotalMinimum',
            'overallGrade'
        ));
    }
}
