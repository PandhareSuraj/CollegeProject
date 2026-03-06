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
        Schema::create('approvals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->index();
            $table->string('approved_by')->index(); // Store email or user identifier instead of FK
            $table->enum('role', ['admin', 'teacher', 'hod', 'principal', 'trust_head', 'provider'])->index();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending')->index();
            $table->text('remarks')->nullable();
            $table->timestamps();
            
            // Foreign key constraints with explicit names
            $table->foreign('request_id', 'fk_approvals_request_id')
                ->references('id')
                ->on('requests')
                ->cascadeOnDelete();
            
            // Composite indexes for approval tracking queries
            $table->index(['request_id', 'approved_by'], 'idx_approvals_req_user');
            $table->index(['request_id', 'role', 'status'], 'idx_approvals_req_role_status');
            $table->index(['status', 'created_at'], 'idx_approvals_status_date');
            
            // Unique constraint: one approval per request per level
            $table->unique(['request_id', 'role'], 'unique_approvals_per_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};
