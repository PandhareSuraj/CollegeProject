<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Campus Store Management System - Add Department & Role to Users Table
     * 
     * Available Roles:
     * - admin: System Administrator with full access
     * - teacher: Faculty member who submits stationary requests
     * - hod: Head of Department who approves departmental requests
     * - principal: College Principal who approves principal-level requests
     * - trust_head: Trust/Organization Head overseeing multiple institutions
     * - provider: Vendor/Supplier who manages products and fulfills orders
     * 
     * All role check methods are available as:
     * $user->isAdmin(), $user->isTeacher(), $user->isHOD(), 
     * $user->isPrincipal(), $user->isTrustHead(), $user->isProvider()
     */
    public function up()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'department_id')) {
                    $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
                }
                if (!Schema::hasColumn('users', 'role')) {
                    $table->enum('role', ['admin','teacher','hod','principal','trust_head','provider'])->comment('User role in the system')->default('teacher');
                }
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'department_id')) {
                    $table->dropForeign(['department_id']);
                    $table->dropColumn('department_id');
                }
                if (Schema::hasColumn('users', 'role')) {
                    $table->dropColumn('role');
                }
            });
        }
    }
};
