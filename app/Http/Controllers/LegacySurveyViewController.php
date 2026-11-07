<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyItem;
use App\Models\SurveyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegacySurveyViewController extends Controller
{
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

        // Update atribut kapal jika diisi
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
