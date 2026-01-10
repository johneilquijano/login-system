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
        Schema::table('tools', function (Blueprint $table) {
            $table->integer('quantity')->default(1)->after('is_maintenance'); // Default to 1 for existing tools
            $table->index(['org_id', 'quantity']); // Index for efficient filtering
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tools', function (Blueprint $table) {
            $table->dropIndex(['org_id', 'quantity']);
            $table->dropColumn('quantity');
        });
    }
};
