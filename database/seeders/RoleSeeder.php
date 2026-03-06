<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the roles table with all available roles
     * 
     * Campus Store Management System Role Hierarchy:
     * 1. admin - System Administrator with full access
     * 2. teacher - Faculty member who can submit requests
     * 3. hod - Head of Department who approves departmental requests
     * 4. principal - College Principal who approves principal-level requests
     * 5. trust_head - Trust/Organization Head who oversees multiple institutions
     * 6. provider - Vendor/Supplier who fulfills orders
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'System Administrator - Full system access, manages all users and configurations across all institutions',
                'permissions' => json_encode(['*']), // All permissions
            ],
            [
                'name' => 'Teacher',
                'slug' => 'teacher',
                'description' => 'Faculty Member - Can submit stationary requests and view their own request status',
                'permissions' => json_encode(['request.create', 'request.view.own']),
            ],
            [
                'name' => 'Head of Department',
                'slug' => 'hod',
                'description' => 'Department Head - Approves departmental requests, manages department budget and resources',
                'permissions' => json_encode(['request.view.department', 'request.approve.department', 'request.create']),
            ],
            [
                'name' => 'Principal',
                'slug' => 'principal',
                'description' => 'College Principal - Approves principal-level requests, manages college policies and procedures',
                'permissions' => json_encode(['request.view.college', 'request.approve.college', 'request.approve.department']),
            ],
            [
                'name' => 'Trust Head',
                'slug' => 'trust_head',
                'description' => 'Trust/Organization Head - Oversees multiple colleges, approves major institutional purchases',
                'permissions' => json_encode(['request.view.all', 'request.approve.all', 'report.view']),
            ],
            [
                'name' => 'Provider',
                'slug' => 'provider',
                'description' => 'Vendor/Supplier - Manages product catalogs, pricing, and fulfills approved orders',
                'permissions' => json_encode(['product.manage', 'order.view', 'order.fulfill']),
            ],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
