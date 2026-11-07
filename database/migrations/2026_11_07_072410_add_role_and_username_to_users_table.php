<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('name');
            $table->string('role')->default('admin_mcm')->after('email'); // admin_mcm, insurance, surveyor, shipowner, broker
            $table->integer('id_level_legacy')->nullable()->after('role');
            $table->bigInteger('reference_id')->nullable()->after('id_level_legacy');
            $table->boolean('is_active')->default(true)->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'role', 'id_level_legacy', 'reference_id', 'is_active']);
        });
    }
};
