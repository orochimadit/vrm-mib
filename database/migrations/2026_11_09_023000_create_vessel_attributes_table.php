<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vessel_attributes')) {
            Schema::create('vessel_attributes', function (Blueprint $table) {
                $table->id('attribute_id');
                $table->unsignedBigInteger('insurance_product_id')->nullable()->index();
                $table->text('category_attr_type')->nullable()->index();
                $table->string('category_attr', 255)->nullable();
                $table->string('label', 255)->nullable();
                $table->string('type', 100)->default('text');
                $table->integer('option_id')->default(0);
                $table->integer('parent')->default(0);
                $table->integer('order')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vessel_attributes');
    }
};
