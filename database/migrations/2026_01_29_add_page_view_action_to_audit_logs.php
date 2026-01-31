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
        // For MySQL, we need to recreate the enum column
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE audit_logs 
                MODIFY COLUMN action ENUM(
                    'login', 'logout', 'create', 'update', 'delete', 
                    'upload', 'download', 'assign', 'sign', 'status_change',
                    'approve', 'deny', 'submit', 'claim', 'complete', 'receive', 'page_view'
                )
            ");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            DB::statement("
                ALTER TABLE audit_logs 
                MODIFY COLUMN action ENUM(
                    'login', 'logout', 'create', 'update', 'delete', 
                    'upload', 'download', 'assign', 'sign', 'status_change',
                    'approve', 'deny', 'submit', 'claim', 'complete', 'receive'
                )
            ");
        }
    }
};
