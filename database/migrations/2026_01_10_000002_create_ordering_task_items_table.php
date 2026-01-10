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
        Schema::create('ordering_task_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('ordering_task_id')->constrained('ordering_tasks')->onDelete('cascade');
            $table->foreignId('inventory_request_id')->constrained('inventory_requests')->onDelete('cascade');
            $table->foreignId('inventory_request_item_id')->constrained('inventory_request_items')->onDelete('cascade');
            $table->string('item_name');
            $table->string('job_number');
            $table->string('model_number');
            $table->unsignedInteger('quantity_approved');
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'ordered', 'received'])->default('pending');
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('received_at')->nullable();
            $table->timestamps();

            $table->index(['org_id', 'ordering_task_id']);
            $table->index(['inventory_request_id']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordering_task_items');
    }
};
