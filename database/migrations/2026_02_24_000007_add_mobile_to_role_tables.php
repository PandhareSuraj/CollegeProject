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
        $tables = ['teachers', 'hods', 'principals', 'trust_heads', 'providers', 'administrators'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table_obj) {
                    if (!Schema::hasColumn($table_obj->getTable(), 'mobile_number')) {
                        $table_obj->string('mobile_number')->unique()->nullable()->after('email');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['teachers', 'hods', 'principals', 'trust_heads', 'providers', 'administrators'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $table_obj) {
                    if (Schema::hasColumn($table_obj->getTable(), 'mobile_number')) {
                        $table_obj->dropUnique(['mobile_number']);
                        $table_obj->dropColumn('mobile_number');
                    }
                });
            }
        }
    }
};
