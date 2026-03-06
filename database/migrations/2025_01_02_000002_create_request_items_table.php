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
        Schema::create('request_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->integer('quantity')->unsigned();
            $table->decimal('price', 10, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();
            
            // Foreign key constraints with explicit names
            $table->foreign('request_id', 'fk_request_items_request_id')
                ->references('id')
                ->on('requests')
                ->cascadeOnDelete();
            
            $table->foreign('product_id', 'fk_request_items_product_id')
                ->references('id')
                ->on('products')
                ->cascadeOnDelete();
            
            // Indexes for common queries
            $table->index(['request_id', 'product_id'], 'idx_request_items_req_prod');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};
