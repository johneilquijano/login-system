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
            // Add org_id if it doesn't exist
            if (!Schema::hasColumn('users', 'org_id')) {
                $table->foreignId('org_id')->nullable()->constrained('organizations')->onDelete('cascade');
            }

            // Add role if it doesn't exist
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['employee', 'admin'])->default('employee')->after('password');
            }

            // Add status if it doesn't exist
            if (!Schema::hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'disabled'])->default('active')->after('role');
            }

            // Add unique constraint on org_id and email
            if (!Schema::hasIndex('users', ['org_id', 'email'])) {
                $table->unique(['org_id', 'email']);
            }

            // Add indices
            if (!Schema::hasIndex('users', 'org_id')) {
                $table->index('org_id');
            }
            if (!Schema::hasIndex('users', 'role')) {
                $table->index('role');
            }
            if (!Schema::hasIndex('users', 'status')) {
                $table->index('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeignIdFor('Organization');
            $table->dropIndex(['org_id', 'email']);
            $table->dropIndex('org_id');
            $table->dropIndex('role');
            $table->dropIndex('status');
            $table->dropColumn(['org_id', 'role', 'status']);
        });
    }
};
