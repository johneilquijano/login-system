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
        Schema::table('inventory_request_items', function (Blueprint $table) {
            $table->string('job_number')->nullable()->after('category');
            $table->string('model_number')->nullable()->after('job_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_request_items', function (Blueprint $table) {
            $table->dropColumn(['job_number', 'model_number']);
        });
    }
};
