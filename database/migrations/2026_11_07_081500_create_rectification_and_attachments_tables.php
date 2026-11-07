<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('survey_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->integer('insurance_product_id')->nullable();
            $table->string('ref_no', 255)->nullable();
            $table->string('category', 500)->nullable();
            $table->text('item_name')->nullable();
            $table->string('risk_value_expected', 255)->nullable();
            $table->integer('risk_value')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id('response_id');
            $table->integer('survey_id')->nullable()->index();
            $table->integer('item_id')->nullable()->index();
            $table->string('response', 255)->nullable();
            $table->string('risk_impact', 255)->nullable();
            $table->integer('risk_rating')->nullable();
            $table->text('remarks_from_surveyor')->nullable();
            $table->timestamps();
        });

        Schema::create('survey_responses_attachment', function (Blueprint $table) {
            $table->id('id_attachment');
            $table->integer('response_id')->nullable()->index();
            $table->string('title', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->text('link')->nullable();
            $table->string('gdrive_id', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('selfassessment_attachment', function (Blueprint $table) {
            $table->id('id_attachment');
            $table->integer('response_id')->nullable()->index();
            $table->string('title', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->text('link')->nullable();
            $table->string('gdrive_id', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('photoalbum_attachment', function (Blueprint $table) {
            $table->id('id_attachment');
            $table->integer('response_id')->nullable()->index();
            $table->string('title', 255)->nullable();
            $table->string('type', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->text('link')->nullable();
            $table->string('gdrive_id', 255)->nullable();
            $table->timestamps();
        });

        Schema::create('survey_responses_deffect_file', function (Blueprint $table) {
            $table->id('id_file');
            $table->integer('id_deffect')->nullable()->index();
            $table->string('type', 255)->nullable();
            $table->string('file', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->text('link')->nullable();
            $table->string('gdrive_id', 150)->nullable();
            $table->timestamps();
        });

        Schema::create('log_rectification', function (Blueprint $table) {
            $table->id('id');
            $table->integer('id_deffect')->nullable()->index();
            $table->text('value')->nullable();
            $table->string('category', 50)->default('rectification_owner')->index();
            $table->timestamp('datetime')->nullable()->index();
            $table->integer('id_admin')->nullable()->index();
            $table->timestamps();
        });

        Schema::create('log_rectification_file', function (Blueprint $table) {
            $table->id('id_log_file');
            $table->integer('id_log_deffect')->nullable()->index();
            $table->string('file_type', 255)->nullable();
            $table->string('file_name', 255)->nullable();
            $table->string('file_title', 255)->nullable();
            $table->string('file_link', 255)->nullable();
            $table->string('file_gdrive_id', 255)->nullable();
            $table->timestamp('datetime')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_rectification_file');
        Schema::dropIfExists('log_rectification');
        Schema::dropIfExists('survey_responses_deffect_file');
        Schema::dropIfExists('photoalbum_attachment');
        Schema::dropIfExists('selfassessment_attachment');
        Schema::dropIfExists('survey_responses_attachment');
        Schema::dropIfExists('survey_responses');
        Schema::dropIfExists('survey_items');
    }
};
