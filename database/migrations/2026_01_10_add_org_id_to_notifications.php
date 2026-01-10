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
        // Add org_id column using raw SQL if it doesn't exist
        if (!DB::getSchemaBuilder()->hasColumn('notifications', 'org_id')) {
            DB::statement('ALTER TABLE notifications ADD COLUMN org_id BIGINT UNSIGNED AFTER user_id');
            DB::statement('ALTER TABLE notifications ADD CONSTRAINT fk_notifications_org_id FOREIGN KEY (org_id) REFERENCES organizations(id) ON DELETE CASCADE');
            DB::statement('CREATE INDEX idx_notifications_org_user ON notifications(org_id, user_id)');
            
            // Set default org_id for existing rows
            DB::statement('UPDATE notifications SET org_id = 1 WHERE org_id IS NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getSchemaBuilder()->hasColumn('notifications', 'org_id')) {
            DB::statement('ALTER TABLE notifications DROP FOREIGN KEY fk_notifications_org_id');
            DB::statement('DROP INDEX idx_notifications_org_user ON notifications');
            DB::statement('ALTER TABLE notifications DROP COLUMN org_id');
        }
    }
};

