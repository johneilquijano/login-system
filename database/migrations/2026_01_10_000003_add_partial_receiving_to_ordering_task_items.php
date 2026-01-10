<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ordering_task_items', function (Blueprint $table) {
            // Add quantity_received column
            $table->unsignedInteger('quantity_received')->default(0)->after('quantity_approved');
        });

        // Change status enum separately to avoid conflicts
        DB::statement("ALTER TABLE ordering_task_items MODIFY status ENUM('pending', 'ordered', 'partially_received', 'received') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ordering_task_items', function (Blueprint $table) {
            $table->dropColumn('quantity_received');
        });

        // Revert status enum to original values
        DB::statement("ALTER TABLE ordering_task_items MODIFY status ENUM('pending', 'ordered', 'received') NOT NULL DEFAULT 'pending'");
    }
};
