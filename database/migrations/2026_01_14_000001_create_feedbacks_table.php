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
        if (Schema::hasTable('feedbacks')) {
            return;
        }
        
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            
            // User context
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('org_id')->constrained('organizations')->onDelete('cascade');
            $table->enum('user_role', ['employee', 'admin'])->default('employee');
            
            // Page context
            $table->string('route')->nullable(); // Named route if available
            $table->string('url_path'); // e.g., /admin/inventory-requests
            $table->integer('viewport_width')->nullable();
            $table->integer('viewport_height')->nullable();
            $table->text('user_agent')->nullable();
            
            // Click coordinates
            $table->integer('click_x')->nullable();
            $table->integer('click_y')->nullable();
            $table->integer('scroll_x')->nullable();
            $table->integer('scroll_y')->nullable();
            
            // Target element metadata
            $table->string('element_tag')->nullable(); // e.g., button, input, div
            $table->string('element_id')->nullable();
            $table->string('element_name')->nullable();
            $table->text('element_classes')->nullable();
            $table->string('element_text')->nullable(); // Trimmed, short
            $table->string('element_aria_label')->nullable();
            $table->string('element_placeholder')->nullable();
            $table->text('element_selector')->nullable(); // Best-effort CSS selector
            $table->text('element_path')->nullable(); // Simplified DOM path
            
            // Feedback content
            $table->enum('category', ['bug', 'ux', 'feature']); // Bug, UX, Feature Request
            $table->text('message'); // User's note/description
            $table->enum('severity', ['low', 'medium', 'high'])->nullable(); // Optional severity
            $table->enum('status', ['new', 'in_review', 'fixed', 'ignored'])->default('new');
            
            // Admin internal notes
            $table->text('admin_notes')->nullable();
            
            // Future screenshot support
            $table->string('screenshot_url')->nullable();
            $table->boolean('screenshot_captured')->default(false);
            
            // Page-relative coordinates and route name
            $table->integer('page_x')->nullable();
            $table->integer('page_y')->nullable();
            $table->string('route_name')->nullable();
            
            $table->timestamps();
            
            // Indexes for common queries
            $table->index(['org_id', 'status']);
            $table->index(['org_id', 'category']);
            $table->index(['user_id', 'created_at']);
            $table->index(['url_path']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
