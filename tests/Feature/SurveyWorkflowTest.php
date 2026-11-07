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
}
