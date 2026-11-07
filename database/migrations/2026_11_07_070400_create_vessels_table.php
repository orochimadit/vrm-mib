<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vessels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ex_name')->nullable();
            $table->string('imo_number')->nullable()->index();
            $table->string('flag')->nullable();
            $table->foreignId('vessel_type_id')->nullable()->constrained('vessel_types')->nullOnDelete();
            $table->string('type_name_legacy')->nullable();
            $table->foreignId('owner_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->foreignId('operator_company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('classification_society')->nullable();
            $table->string('class_recommendation_issued')->nullable();
            $table->string('exemption_certificates_issued')->nullable();
            $table->smallInteger('year_built')->nullable();
            $table->string('place_built')->nullable();
            $table->integer('grt')->nullable();
            $table->integer('dwt')->nullable();
            $table->decimal('tsi', 20, 2)->default(0);
            $table->string('dimensions')->nullable();
            $table->text('recent_cargoes_carried')->nullable();
            $table->string('recent_port_of_call')->nullable();
            $table->string('trading_area')->nullable();
            $table->date('date_of_last_drydock')->nullable();
            $table->date('date_of_next_drydock')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vessels');
    }
};
