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
        Schema::table('feedbacks', function (Blueprint $table) {
            // Add page-relative coordinates if they don't exist
            if (!Schema::hasColumn('feedbacks', 'page_x')) {
                $table->integer('page_x')->nullable()->after('click_y');
            }
            if (!Schema::hasColumn('feedbacks', 'page_y')) {
                $table->integer('page_y')->nullable()->after('page_x');
            }
            // Add route name if it doesn't exist
            if (!Schema::hasColumn('feedbacks', 'route_name')) {
                $table->string('route_name')->nullable()->after('route');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedbacks', function (Blueprint $table) {
            $table->dropColumn(['page_x', 'page_y', 'route_name']);
        });
    }
};
