<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('colleges')) {
            Schema::create('colleges', function (Blueprint $table) {
                $table->id();
                $table->foreignId('sanstha_id')->constrained('sansthas')->onDelete('cascade');
                $table->string('name');
                $table->text('address')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('colleges');
    }
};
