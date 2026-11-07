<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Casualty Reference
        if (! Schema::hasTable('casualty_refs')) {
            Schema::create('casualty_refs', function (Blueprint $table) {
                $table->id();
                $table->string('casualty');
                $table->timestamps();
            });
        }

        // 2. Claim Adjusters
        if (! Schema::hasTable('claim_adjusters')) {
            Schema::create('claim_adjusters', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('address')->nullable();
                $table->string('phone', 50)->nullable();
                $table->string('email', 100)->nullable();
                $table->string('website', 100)->nullable();
                $table->timestamps();
            });
        }

        // 3. Insurance Products
        if (! Schema::hasTable('insurance_products')) {
            Schema::create('insurance_products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('insurance_company_id')->constrained('insurance_companies')->cascadeOnDelete();
                $table->integer('risk_treatment')->nullable();
                $table->string('risk_treatment_label')->nullable();
                $table->string('name');
                $table->text('description')->nullable();
                $table->text('coverage_details')->nullable();
                $table->decimal('price', 15, 2)->default(0);
                $table->integer('machinery_particulars')->default(0);
                $table->integer('additional_machinery_spaces')->default(0);
                $table->integer('vessel_pic')->default(0);
                $table->timestamps();
            });
        }

        // 4. Claims
        if (! Schema::hasTable('claims')) {
            Schema::create('claims', function (Blueprint $table) {
                $table->id();
                $table->foreignId('vessel_id')->nullable()->constrained('vessels')->nullOnDelete();
                $table->foreignId('insurance_company_id')->nullable()->constrained('insurance_companies')->nullOnDelete();
                $table->foreignId('insurance_product_id')->nullable()->constrained('insurance_products')->nullOnDelete();
                $table->string('portfolio_consultant')->nullable(); // 'consultant', 'portfolio', 'all_portfolio'
                $table->text('accident')->nullable();
                $table->foreignId('casualty_ref_id')->nullable()->constrained('casualty_refs')->nullOnDelete();
                $table->date('case_handled')->nullable();
                $table->date('date_of_loss')->nullable();
                $table->time('time_of_loss')->nullable();
                $table->string('location')->nullable();
                $table->decimal('estimated_claim', 15, 2)->nullable();
                $table->string('estimated_claim_currency', 10)->nullable();
                $table->decimal('amount', 15, 2)->nullable();
                $table->string('deductible')->nullable();
                $table->string('deductible_currency', 10)->nullable();
                $table->string('deductible_input', 20)->default('number');
                $table->string('policy_number')->nullable();
                $table->date('period_of_policy_start')->nullable();
                $table->date('period_of_policy_end')->nullable();
                $table->string('mic_bm')->nullable();
                $table->string('mic_clp')->nullable();
                $table->decimal('tsi_lol', 15, 2)->nullable();
                $table->string('tsi_lol_currency', 10)->nullable();
                $table->string('settled_claim_amount_currency', 10)->nullable();
                $table->decimal('settled_claim_amount', 15, 2)->nullable();
                $table->string('status', 50)->default('ongoing');
                $table->text('status_description')->nullable();
                $table->date('settled_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('claims');
        Schema::dropIfExists('insurance_products');
        Schema::dropIfExists('claim_adjusters');
        Schema::dropIfExists('casualty_refs');
    }
};
