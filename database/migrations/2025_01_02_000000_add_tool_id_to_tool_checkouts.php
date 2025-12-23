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
            // Add tool_id column after tool_name
            if (!Schema::hasColumn('tool_checkouts', 'tool_id')) {
                $table->foreignId('tool_id')->nullable()->after('user_id')->constrained('tools')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tool_checkouts', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['tool_id']);
            $table->dropColumn('tool_id');
        });
    }
};
