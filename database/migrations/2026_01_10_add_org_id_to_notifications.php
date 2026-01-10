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
        // Only add org_id if it doesn't exist
        if (Schema::hasTable('notifications') && !Schema::hasColumn('notifications', 'org_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->unsignedBigInteger('org_id')->after('user_id')->default(1);
                $table->foreign('org_id')->references('id')->on('organizations')->onDelete('cascade');
                $table->index(['org_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('notifications') && Schema::hasColumn('notifications', 'org_id')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->dropForeign(['org_id']);
                $table->dropIndex(['org_id', 'user_id']);
                $table->dropColumn('org_id');
            });
        }
    }
};
