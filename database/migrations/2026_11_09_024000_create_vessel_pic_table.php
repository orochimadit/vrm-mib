<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('vessel_pic')) {
            Schema::create('vessel_pic', function (Blueprint $table) {
                $table->id('id_pic');
                $table->unsignedBigInteger('insurance_product_id')->nullable()->index();
                $table->string('position', 255)->nullable();
                $table->string('name', 255)->nullable();
                $table->string('phone_number', 255)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('vessel_pic');
    }
};
