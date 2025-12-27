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
        Schema::table('inventory_requests', function (Blueprint $table) {
            // Modify the status column to include 'cancelled'
            $table->enum('status', ['draft', 'submitted', 'approved', 'denied', 'fulfilled', 'cancelled'])
                ->default('draft')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            // Revert to the original enum without 'cancelled'
            $table->enum('status', ['draft', 'submitted', 'approved', 'denied', 'fulfilled'])
                ->default('draft')
                ->change();
        });
    }
};
