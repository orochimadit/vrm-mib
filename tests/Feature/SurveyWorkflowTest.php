<?php

namespace Tests\Feature;

use App\Filament\Resources\Surveys\SurveyResource;
use App\Models\Survey;
use App\Models\User;
use Tests\TestCase;

class SurveyWorkflowTest extends TestCase
{
    public function test_admin_can_access_surveys_and_deficiency_and_checklist(): void
    {
        $admin = User::where('role', 'admin_mcm')->first();
        $survey = Survey::whereHas('defects')->first();

        $this->actingAs($admin);

        $this->get('/admin/surveys')->assertStatus(200);
        $this->get("/admin/surveys/{$survey->id}/checklist")->assertStatus(200);
        $this->get("/admin/surveys/{$survey->id}/deficiency")->assertStatus(200);
    }

    public function test_surveyor_can_access_surveys_and_assigned_pages(): void
    {
        $user = User::where('role', 'surveyor')->first();
        $this->actingAs($user);
        $this->get('/admin/surveys')->assertStatus(200);

        // Find survey assigned to this surveyor
        $survey = SurveyResource::getEloquentQuery()->whereHas('defects')->first();
        if ($survey) {
            $this->get("/admin/surveys/{$survey->id}/checklist")->assertStatus(200);
            $this->get("/admin/surveys/{$survey->id}/deficiency")->assertStatus(200);
        }
    }

    public function test_insurance_can_access_surveys_and_assigned_pages(): void
    {
        $user = User::where('role', 'insurance')->first();
        $this->actingAs($user);
        $this->get('/admin/surveys')->assertStatus(200);

        $survey = SurveyResource::getEloquentQuery()->whereHas('defects')->first();
        if ($survey) {
            $this->get("/admin/surveys/{$survey->id}/checklist")->assertStatus(200);
            $this->get("/admin/surveys/{$survey->id}/deficiency")->assertStatus(200);
        }
    }

    public function test_shipowner_can_access_surveys(): void
    {
        $user = User::where('role', 'shipowner')->first();
        $this->actingAs($user);
        $this->get('/admin/surveys')->assertStatus(200);
    }

    public function test_broker_can_access_surveys(): void
    {
        $user = User::where('role', 'broker')->first();
        $this->actingAs($user);
        $this->get('/admin/surveys')->assertStatus(200);
    }

    public function test_log_rectification_files_relation_and_modal(): void
    {
        $defectWithArchivedFiles = \App\Models\SurveyDefect::whereHas('logRectificationFiles')->first();
        $this->assertNotNull($defectWithArchivedFiles, 'Defect with logRectificationFiles should exist');
        $this->assertGreaterThan(0, $defectWithArchivedFiles->logRectificationFiles()->count());

        $html = view('filament.modals.defect-log-files', ['record' => $defectWithArchivedFiles])->render();
        $this->assertStringContainsString('Dihapus / Diarsipkan', $html);
    }

    public function test_admin_can_access_all_legacy_cloned_admin_pages(): void
    {
        $admin = User::where('role', 'admin_mcm')->first();
        $this->actingAs($admin);

        $routes = [
            '/admin/services',
            '/admin/claims',
            '/admin/casualty-refs',
            '/admin/companies',
            '/admin/vessels',
            '/admin/insurance-products',
            '/admin/surveyors',
            '/admin/claim-adjusters',
            '/admin/insurance-companies',
            '/admin/brokers',
            '/admin/users',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200, "Route {$route} failed with status " . $response->getStatusCode());
        }

        // Test filtered insurance product page specifically
        $responseFiltered = $this->get('/admin/insurance-products?tableFilters[insurance_company_id][value]=8');
        $responseFiltered->assertStatus(200);
    }

    public function test_document_route_handles_existing_and_missing_files(): void
    {
        $existing = $this->get('/document/attachment/CLASS_CERTIFICATE.pdf');
        $existing->assertStatus(200);
        $this->assertEquals('application/pdf', $existing->headers->get('content-type'));

        // Missing file should return 404 with friendly explanation, NEVER 403 Forbidden
        $missing = $this->get('/document/attachment/notulensi_05_okt1.pdf');
        $missing->assertStatus(404);
        $missing->assertSee('Berkas Fisik Belum Tersedia');
    }
}
