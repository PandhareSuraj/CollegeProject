<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('requests') && ! Schema::hasColumn('requests', 'order_id')) {
            Schema::table('requests', function (Blueprint $table) {
                $table->foreignId('order_id')->nullable()->after('total_amount')->constrained('orders')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('requests') && Schema::hasColumn('requests', 'order_id')) {
            Schema::table('requests', function (Blueprint $table) {
                $table->dropForeign(['order_id']);
                $table->dropColumn('order_id');
            });
        }
    }
};
