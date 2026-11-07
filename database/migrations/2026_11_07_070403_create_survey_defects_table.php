<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_defects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->string('item_category')->nullable(); // Hull, Machinery, Safety, Class
            $table->string('item_name')->nullable();
            $table->text('explanation')->nullable();
            $table->text('recommendation_rectification')->nullable();
            $table->text('rectification_owner')->nullable();
            $table->integer('rectification_time_days')->nullable();
            $table->text('remark_defect')->nullable();
            $table->string('status')->default('Outstanding'); // Outstanding, Approve, Decline
            $table->string('rectification_status')->default('Pending'); // Pending, In Progress, Completed
            $table->string('source')->default('hasil_survey'); // hasil_survey, manual
            $table->jsonb('attachments')->nullable(); // Uploaded photos / documents
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_defects');
    }
};
