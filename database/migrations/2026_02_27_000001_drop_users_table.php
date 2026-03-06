<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            Schema::dropIfExists('users');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // intentionally left blank - recreate users via previous migration if needed
    }
};
