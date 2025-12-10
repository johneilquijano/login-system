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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('org_id')->constrained('organizations')->onDelete('cascade');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('password');
            $table->enum('role', ['employee', 'admin'])->default('employee');
            $table->enum('status', ['active', 'disabled'])->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('remember_token')->nullable();
            $table->timestamps();

            // Unique constraint per organization
            $table->unique(['org_id', 'email']);
            
            // Indices for common queries
            $table->index('org_id');
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
