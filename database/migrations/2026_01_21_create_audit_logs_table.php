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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            
            // Organization & User scope
            $table->foreignId('org_id')->constrained('organizations')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('user_role')->nullable()->comment('User role at time of action');
            $table->foreignId('impersonator_id')->nullable()->constrained('users', 'id')->onDelete('set null');
            
            // Action metadata
            $table->enum('action', [
                'login', 'logout', 'create', 'update', 'delete', 
                'upload', 'download', 'assign', 'sign', 'status_change',
                'approve', 'deny', 'submit', 'claim', 'complete'
            ])->index();
            $table->string('entity_type')->index()->comment('e.g., user, document, inventory_request, tool');
            $table->unsignedBigInteger('entity_id')->nullable()->index();
            $table->text('description')->comment('Human-readable summary of the action');
            
            // Request context (replaces page views)
            $table->string('url_path')->nullable()->comment('The URL/route path where action occurred');
            $table->string('route_name')->nullable()->comment('Route name if available');
            $table->string('method', 10)->nullable()->comment('HTTP method: GET, POST, PUT, DELETE, etc');
            $table->string('ip_address', 45)->nullable()->comment('IPv4 or IPv6');
            $table->text('user_agent')->nullable();
            
            // Change data (optional, non-sensitive)
            $table->json('metadata')->nullable()->comment('Non-sensitive metadata: assigned_to_user_id, filename, status_change details, etc');
            
            // Timestamps
            $table->timestamps();
            
            // Performance indexes
            $table->index(['org_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['action', 'created_at']);
            $table->index(['entity_type', 'entity_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
