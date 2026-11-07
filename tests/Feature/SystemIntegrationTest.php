<?php

namespace Tests\Feature;

use App\Filament\Resources\Surveys\SurveyResource;
use App\Models\Broker;
use App\Models\CasualtyRef;
use App\Models\Claim;
use App\Models\ClaimAdjuster;
use App\Models\Company;
use App\Models\InsuranceCompany;
use App\Models\InsuranceProduct;
use App\Models\LogRectification;
use App\Models\LogRectificationFile;
use App\Models\Survey;
use App\Models\SurveyDefect;
use App\Models\SurveyDefectFile;
use App\Models\SurveyItem;
use App\Models\SurveyResponse;
use App\Models\SurveyResponseAttachment;
use App\Models\Surveyor;
use App\Models\User;
use App\Models\Vessel;
use App\Models\VesselType;
use Tests\TestCase;
use ZipArchive;

class SystemIntegrationTest extends TestCase
{
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::where('role', 'admin_mcm')->first() ?? User::first();
    }

    /**
     * 1. INTEGRASI SIKLUS HIDUP LENGKAP SURVEI (END-TO-END WORKFLOW)
     * Alur: Penugasan -> Form Condition -> Checklist -> Defisiensi -> Respon Perbaikan -> Verifikasi
     */
    public function test_end_to_end_survey_lifecycle_integration(): void
    {
        $this->actingAs($this->admin);

        // A. Ambil data master kapal & surveyor
        $vessel = Vessel::first();
        $surveyor = Surveyor::first();
        $insurance = InsuranceCompany::first();

        $this->assertNotNull($vessel, 'Data master vessel harus tersedia');

        // B. Buat entri survei baru
        $survey = Survey::create([
            'vessel_id' => $vessel->id,
            'surveyor_id' => $surveyor?->id,
            'insurance_company_id' => $insurance?->id,
            'surveyor_name' => 'PT Marine Surveyor Indonesia',
            'location' => 'Pelabuhan Tanjung Priok, Jakarta',
            'condition_type' => 'Self Assessment',
            'date_of_survey' => now()->format('Y-m-d'),
            'due_date' => now()->addDays(30)->format('Y-m-d'),
            'policy_period_start' => now()->format('Y-m-d'),
            'policy_period_end' => now()->addYear()->format('Y-m-d'),
            'status' => 'Outstanding',
        ]);

        $this->assertDatabaseHas('surveys', ['id' => $survey->id]);

        // C. Simulasi Pengisian Form Condition (Update Lokasi & Surveyor)
        $survey->update([
            'location' => 'Dermaga Merak Mas, Banten',
            'surveyor_name' => 'Capt. Bambang Suryo',
        ]);
        $this->assertEquals('Dermaga Merak Mas, Banten', $survey->fresh()->location);

        // D. Tambahkan Item Checklist Pemeriksaan Lapangan
        $item = SurveyItem::first();
        $response = SurveyResponse::create([
            'survey_id' => $survey->id,
            'item_id' => $item?->id_item ?? 1,
            'response' => '1', // Poor / Cacat
            'remarks_from_surveyor' => 'Ditemukan karat berat pada windlasses',
        ]);

        // E. Unggah Lampiran Bukti Pemeriksaan pada Field
        $attachment = SurveyResponseAttachment::create([
            'response_id' => $response->response_id,
            'title' => 'Foto Bukti Kerusakan Windlass',
            'type' => 'file',
            'file' => 'CLASS_CERTIFICATE.pdf',
        ]);
        $this->assertTrue($attachment->has_physical_file);
        $this->assertStringContainsString('/document/attachment/', $attachment->file_url);

        // F. Pencatatan Temuan Cacat Teknis (List of Deficiency)
        $defect = SurveyDefect::create([
            'survey_id' => $survey->id,
            'item_category' => 'Hull & Deck Machinery',
            'item_name' => 'Hydraulic Brake Windlass',
            'explanation' => 'Hydraulic brake windlass bocor dan berkarat',
            'recommendation_rectification' => 'Ganti seal hidrolik dan bersihkan kerak karat',
            'status' => 'Outstanding',
        ]);

        // G. Ship Owner Merespon Perbaikan (Rectification)
        $logRectification = LogRectification::create([
            'id_deffect' => $defect->id,
            'remark' => 'Seal hidrolik baru telah dipasang oleh teknisi mesin kapal',
        ]);

        $logFile = LogRectificationFile::create([
            'id_log_deffect' => $logRectification->id,
            'file_name' => 'CLASS_CERTIFICATE.pdf',
            'file_title' => 'Bukti Penggantian Seal Hidrolik',
            'file_type' => 'image',
            'datetime' => now(),
        ]);
        $this->assertTrue($logFile->has_physical_file);

        // H. Surveyor Memverifikasi & Menyetujui Perbaikan
        $defect->update([
            'rectification_owner' => 'Perbaikan telah selesai dikerjakan sesuai standar kelas',
            'status' => 'Close',
        ]);
        $this->assertEquals('Close', $defect->fresh()->status);

        // I. Penyelesaian Survei (Completed)
        $survey->update([
            'status' => 'Completed',
            'risk_score' => 88.5,
            'risk_rating' => 'Satisfactory',
        ]);
        $this->assertEquals('Completed', $survey->fresh()->status);

        // Bersihkan data uji survei
        $survey->delete();
    }

    /**
     * 2. INTEGRASI SISTEM DOKUMEN & PACKAGING ZIP
     */
    public function test_document_streaming_and_zip_packaging_integration(): void
    {
        $this->actingAs($this->admin);

        // A. Streaming dokumen fisik yang ada di server
        $responseExisting = $this->get('/document/attachment/CLASS_CERTIFICATE.pdf');
        $responseExisting->assertStatus(200);
        $this->assertEquals('application/pdf', $responseExisting->headers->get('content-type'));

        // B. Penanganan berkas historis yang tidak ada (tanpa 403 Forbidden)
        $responseMissing = $this->get('/document/attachment/file_tidak_ada_di_backup.pdf');
        $responseMissing->assertStatus(404);
        $responseMissing->assertSee('Berkas Fisik Belum Tersedia');

        // C. Fallback route /storage/attachment/{filename}
        $responseFallback = $this->get('/storage/attachment/CLASS_CERTIFICATE.pdf');
        $responseFallback->assertStatus(200);

        // D. Uji Pembuatan Arsip ZIP Dokumen Survei Secara Otomatis
        $zipFileName = 'test-integration-survey-attachments.zip';
        $zipPath = storage_path("app/public/{$zipFileName}");

        $zip = new ZipArchive();
        $this->assertTrue($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE));
        
        $sampleFile = storage_path('app/public/attachments/CLASS_CERTIFICATE.pdf');
        if (file_exists($sampleFile)) {
            $zip->addFile($sampleFile, 'CLASS_CERTIFICATE.pdf');
        }
        $zip->close();

        $this->assertFileExists($zipPath);
        $this->assertGreaterThan(0, filesize($zipPath));

        // Hapus file zip sementara
        if (file_exists($zipPath)) {
            unlink($zipPath);
        }
    }

    /**
     * 3. INTEGRASI LAPORAN LENGKAP & CETAK KONDISI SURVEI (1:1 RESULT.PHP LEGACY)
     */
    public function test_survey_condition_report_generation(): void
    {
        $this->actingAs($this->admin);

        $survey = Survey::first();
        $this->assertNotNull($survey, 'Harus ada data survei untuk pengujian laporan');

        $response = $this->get(route('surveys.report', $survey));
        $response->assertStatus(200);
        $response->assertSee('PT MIB');
        $response->assertSee('Condition Survey & Marine Risk Assessment Form');
        $response->assertSee('Result & Grading Evaluation');
        $response->assertSee('Resume Vessel Condition From Surveyor');
        $response->assertSee('List of Deficiencies & Rectification Status');

        // Uji Tampilan Form 1:1 Image 2 (Vessel Survey Form)
        $responseForm = $this->get(route('surveys.legacy.form', $survey));
        $responseForm->assertStatus(200);
        $responseForm->assertSee('Vessel Survey Form');
        $responseForm->assertSee('Insurance Product');
        $responseForm->assertSee('Vessel Attributes');
        $responseForm->assertSee('Person In Charge');

        // Uji Tampilan Result 1:1 Image 1 (Survey Result)
        $responseResult = $this->get(route('surveys.legacy.result', $survey));
        $responseResult->assertStatus(200);
        $responseResult->assertSee('Survey Result');
        $responseResult->assertSee('GENERAL INFORMATION AND VESSEL\'S DETAIL', false);
        $responseResult->assertSee('Insurance Review');
    }

    /**
     * 4. INTEGRASI SELURUH 10 MODUL NAVIGASI SIDEBAR SESUAI SCREENSHOT
     */
    public function test_all_10_sidebar_modules_integration(): void
    {
        $this->actingAs($this->admin);

        $modules = [
            '1. Services Hub' => '/admin/services',
            '2. Vessel & Owner Company' => '/admin/companies',
            '3. Product Insurance' => '/admin/insurance-products',
            '4. Surveyor Company' => '/admin/surveyors',
            '5. Vessel Risk Management' => '/admin/surveys',
            '6. Insurance Companies (Dropdown)' => '/admin/insurance-companies',
            '7. Adjusters (Dropdown)' => '/admin/claim-adjusters',
            '8. Broker/Partners' => '/admin/brokers',
            '9. Vessel Type' => '/admin/vessel-types',
            '10. Management Administrator' => '/admin/users',
        ];

        foreach ($modules as $label => $route) {
            $response = $this->get($route);
            $response->assertStatus(200, "Modul {$label} ({$route}) gagal dimuat.");
        }
    }

    /**
     * 4. INTEGRASI RELASI DATA SILANG (CROSS-ENTITY RELATIONSHIPS)
     */
    public function test_cross_entity_data_relationships_integration(): void
    {
        $this->actingAs($this->admin);

        // A. Relasi Company -> Vessels (Tombol [Vessel] Aksi Cepat)
        $company = Company::has('ownedVessels')->first();
        if ($company) {
            $response = $this->get("/admin/vessels?tableFilters[company_id][value]={$company->id}");
            $response->assertStatus(200);
        }

        // B. Relasi Insurance Company -> 65 Insurance Products
        $insCompany = InsuranceCompany::has('products')->first();
        if ($insCompany) {
            $response = $this->get("/admin/insurance-products?tableFilters[insurance_company_id][value]={$insCompany->id}");
            $response->assertStatus(200);
        }

        // C. Relasi Claims & Casualty References (22 Klaim Historis)
        $responseClaims = $this->get('/admin/claims');
        $responseClaims->assertStatus(200);

        $responseCasualty = $this->get('/admin/casualty-refs');
        $responseCasualty->assertStatus(200);

        $this->assertGreaterThan(0, Claim::count(), 'Data klaim historis harus ada');
        $this->assertGreaterThan(0, CasualtyRef::count(), 'Data referensi insiden harus ada');
        $this->assertGreaterThan(0, InsuranceProduct::count(), '65 produk asuransi harus ada');
    }

    /**
     * 5. INTEGRASI KONTROL AKSES ROLE INDIVIDUAL (RBAC)
     */
    public function test_surveyor_role_access(): void
    {
        $user = User::where('role', 'surveyor')->first();
        if ($user) {
            $this->actingAs($user);
            $this->get('/admin/surveys')->assertStatus(200);
        }
    }

    public function test_insurance_role_access(): void
    {
        $user = User::where('role', 'insurance')->first();
        if ($user) {
            $this->actingAs($user);
            $this->get('/admin/surveys')->assertStatus(200);
        }
    }

    public function test_shipowner_role_access(): void
    {
        $user = User::where('role', 'shipowner')->first();
        if ($user) {
            $this->actingAs($user);
            $this->get('/admin/surveys')->assertStatus(200);
        }
    }

    public function test_broker_role_access(): void
    {
        $user = User::where('role', 'broker')->first();
        if ($user) {
            $this->actingAs($user);
            $this->get('/admin/surveys')->assertStatus(200);
        }
    }
}
