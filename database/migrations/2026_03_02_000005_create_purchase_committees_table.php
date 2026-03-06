<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        if (!Schema::hasTable('purchase_committees')) {
            Schema::create('purchase_committees', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('committee_user')) {
            Schema::create('committee_user', function (Blueprint $table) {
                $table->foreignId('committee_id')->constrained('purchase_committees')->onDelete('cascade');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                $table->primary(['committee_id', 'user_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('committee_user');
        Schema::dropIfExists('purchase_committees');
    }
};
