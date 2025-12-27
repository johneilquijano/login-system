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
        Schema::create('inventory_request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inventory_request_id');
            $table->string('item_name');
            $table->string('category')->nullable();
            $table->unsignedInteger('quantity');
            $table->text('notes')->nullable();
            $table->unsignedInteger('fulfilled_quantity')->default(0);
            $table->timestamps();

            $table->foreign('inventory_request_id')
                ->references('id')
                ->on('inventory_requests')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_request_items');
    }
};
