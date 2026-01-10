<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to make user_id nullable
        // This avoids the need for doctrine/dbal
        DB::statement('ALTER TABLE documents MODIFY COLUMN user_id BIGINT UNSIGNED NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE documents MODIFY COLUMN user_id BIGINT UNSIGNED NOT NULL');
    }
};
