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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('department_id')->index();
            $table->string('requested_by')->index(); // Store email or user identifier instead of FK
            $table->enum('status', [
                'pending',
                'hod_approved',
                'principal_approved',
                'trust_approved',
                'sent_to_provider',
                'completed',
                'rejected'
            ])->default('pending')->index();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->timestamps();
            
            // Foreign key constraints with explicit names
            $table->foreign('department_id', 'fk_requests_department_id')
                ->references('id')
                ->on('departments')
                ->cascadeOnDelete();
            
            // Composite indexes for common queries
            $table->index(['department_id', 'requested_by'], 'idx_requests_dept_user');
            $table->index(['status', 'created_at'], 'idx_requests_status_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
