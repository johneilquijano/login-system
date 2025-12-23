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
        Schema::table('tool_checkouts', function (Blueprint $table) {
            // Add action_type to track: checkout, return, maintenance_on, maintenance_off
            $table->string('action_type')->default('checkout')->after('status');
            // Add maintenance_reason for admin maintenance notes
            $table->text('maintenance_reason')->nullable()->after('action_type');
            // Keep user_id non-nullable to track which admin performed the action
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tool_checkouts', function (Blueprint $table) {
            $table->dropColumn('action_type');
        });
    }
};
