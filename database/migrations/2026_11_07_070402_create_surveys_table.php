<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vessel_id')->constrained('vessels')->cascadeOnDelete();
            $table->foreignId('surveyor_id')->nullable()->constrained('surveyors')->nullOnDelete();
            $table->foreignId('broker_id')->nullable()->constrained('brokers')->nullOnDelete();
            $table->foreignId('insurance_company_id')->nullable()->constrained('insurance_companies')->nullOnDelete();
            $table->string('surveyor_name')->nullable();
            $table->string('condition_type')->default('Survey'); // Self Assessment, Photo Album, Survey, Underwriting
            $table->string('status')->default('Outstanding'); // Outstanding, Completed, Cancelled
            $table->string('form_status')->default('draft'); // draft, complete
            $table->string('location')->nullable();
            $table->date('date_of_survey')->nullable();
            $table->date('due_date')->nullable();
            $table->text('concern_from_surveyor')->nullable();
            $table->text('resume_from_surveyor')->nullable();
            $table->text('summary')->nullable();
            $table->date('policy_period_start')->nullable();
            $table->date('policy_period_end')->nullable();
            $table->jsonb('inspection_data')->nullable(); // Flexible JSONB for Hull, Machinery, Safety items
            $table->decimal('risk_score', 5, 2)->nullable();
            $table->string('risk_rating')->nullable(); // Low, Moderate, High, Critical
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surveys');
    }
};
