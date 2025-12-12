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
        Schema::create('tool_checkouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tool_name');
            $table->text('description')->nullable();
            $table->string('serial_number')->nullable();
            $table->string('status')->default('checked_out'); // checked_out, returned, maintenance
            $table->timestamp('checked_out_date')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->timestamp('returned_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tool_checkouts');
    }
};
