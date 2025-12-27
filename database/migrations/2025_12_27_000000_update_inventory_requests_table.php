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
        Schema::table('inventory_requests', function (Blueprint $table) {
            // Add new columns if they don't exist
            if (!Schema::hasColumn('inventory_requests', 'request_title')) {
                $table->string('request_title')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('inventory_requests', 'priority')) {
                $table->enum('priority', ['normal', 'urgent'])->default('normal')->after('reason');
            }
            if (!Schema::hasColumn('inventory_requests', 'needed_by_date')) {
                $table->date('needed_by_date')->nullable()->after('priority');
            }
            if (!Schema::hasColumn('inventory_requests', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('inventory_requests', 'denied_at')) {
                $table->timestamp('denied_at')->nullable()->after('approved_at');
            }
            if (!Schema::hasColumn('inventory_requests', 'denied_by')) {
                $table->unsignedBigInteger('denied_by')->nullable()->after('approved_by');
            }
            if (!Schema::hasColumn('inventory_requests', 'denied_reason')) {
                $table->text('denied_reason')->nullable()->after('denied_by');
            }

            // Drop old columns if they exist
            if (Schema::hasColumn('inventory_requests', 'item_name')) {
                $table->dropColumn('item_name');
            }
            if (Schema::hasColumn('inventory_requests', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('inventory_requests', 'quantity_requested')) {
                $table->dropColumn('quantity_requested');
            }
            if (Schema::hasColumn('inventory_requests', 'approval_notes')) {
                $table->dropColumn('approval_notes');
            }
            if (Schema::hasColumn('inventory_requests', 'rejection_reason')) {
                $table->dropColumn('rejection_reason');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_requests', function (Blueprint $table) {
            if (Schema::hasColumn('inventory_requests', 'request_title')) {
                $table->dropColumn('request_title');
            }
            if (Schema::hasColumn('inventory_requests', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('inventory_requests', 'needed_by_date')) {
                $table->dropColumn('needed_by_date');
            }
            if (Schema::hasColumn('inventory_requests', 'admin_notes')) {
                $table->dropColumn('admin_notes');
            }
            if (Schema::hasColumn('inventory_requests', 'denied_at')) {
                $table->dropColumn('denied_at');
            }
            if (Schema::hasColumn('inventory_requests', 'denied_by')) {
                $table->dropColumn('denied_by');
            }
            if (Schema::hasColumn('inventory_requests', 'denied_reason')) {
                $table->dropColumn('denied_reason');
            }
        });
    }
};
