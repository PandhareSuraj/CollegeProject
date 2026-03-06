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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->index('idx_product_name');
            $table->text('description')->nullable();
            $table->integer('stock_quantity')->default(0)->index('idx_product_stock');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            
            // Indexes for common queries
            $table->index('created_at', 'idx_product_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
