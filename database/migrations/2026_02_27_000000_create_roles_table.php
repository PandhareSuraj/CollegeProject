<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Campus Store Management System - Role Definitions Table
     * 
     * This table defines all available user roles in the system.
     * Each institution has the following role hierarchy:
     * 
     * 1. ADMIN - System Administrator
     *    - Full system access
     *    - Manages all users and configurations
     *    - Global access across all colleges/institutions
     * 
     * 2. TRUST_HEAD - Trust/Organization Head
     *    - Oversees multiple colleges/institutions
     *    - Manages institutional policies and procedures
     *    - Approves major purchase requests
     * 
     * 3. PRINCIPAL - College Principal
     *    - Head of a single college/institution
     *    - Approves requests within their college
     *    - Manages college-level policies
     * 
     * 4. HOD - Head of Department
     *    - Department-level authority
     *    - Approves departmental purchase requests
     *    - Manages department budget and resources
     * 
     * 5. PROVIDER - Vendor/Supplier
     *    - Provides items/services to the institution
     *    - Manages product catalogs and pricing
     *    - Fulfills approved orders
     * 
     * 6. TEACHER - Faculty Member
     *    - Regular user/requestor
     *    - Can submit stationary requests
     *    - Can view their own requests
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
