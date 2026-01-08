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
        Schema::table('users', function (Blueprint $table) {
            $table->string('direct_access_token')->nullable()->unique()->after('is_super_admin');
            $table->timestamp('direct_access_token_expires_at')->nullable()->after('direct_access_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['direct_access_token', 'direct_access_token_expires_at']);
        });
    }
};
