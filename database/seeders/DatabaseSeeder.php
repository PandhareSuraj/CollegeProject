<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Department;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create departments (idempotent)
        $csDept = Department::firstOrCreate(
            ['name' => 'Computer Science'],
            ['description' => 'Department of Computer Science & Engineering']
        );

        $eceDept = Department::firstOrCreate(
            ['name' => 'Electronics & Communication'],
            ['description' => 'Department of Electronics & Communication Engineering']
        );

        $meDept = Department::firstOrCreate(
            ['name' => 'Mechanical Engineering'],
            ['description' => 'Department of Mechanical Engineering']
        );

        // Create or update admin user (Administrator model) - ensure password is hashed and email is verified
        \App\Models\Administrator::updateOrCreate([
            'email' => 'admin@campus.test',
        ],[
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create or update principal (mark verified)
        \App\Models\Principal::updateOrCreate([
            'email' => 'principal@campus.test',
        ],[
            'name' => 'Dr. Principal Kumar',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create or update trust head (mark verified)
        \App\Models\TrustHead::updateOrCreate([
            'email' => 'trusthead@campus.test',
        ],[
            'name' => 'Mr. Trust Head Singh',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create or update HODs (mark verified)
        \App\Models\Hod::updateOrCreate([
            'email' => 'hod.cs@campus.test',
        ],[
            'name' => 'Dr. CS HOD Sharma',
            'password' => bcrypt('password'),
            'department_id' => $csDept->id,
            'email_verified_at' => now(),
        ]);

        \App\Models\Hod::updateOrCreate([
            'email' => 'hod.ece@campus.test',
        ],[
            'name' => 'Dr. ECE HOD Verma',
            'password' => bcrypt('password'),
            'department_id' => $eceDept->id,
            'email_verified_at' => now(),
        ]);

        \App\Models\Hod::updateOrCreate([
            'email' => 'hod.me@campus.test',
        ],[
            'name' => 'Dr. ME HOD Patel',
            'password' => bcrypt('password'),
            'department_id' => $meDept->id,
            'email_verified_at' => now(),
        ]);

        // Create or update teachers (mark verified)
        \App\Models\Teacher::updateOrCreate([
            'email' => 'teacher.cs1@campus.test',
        ],[
            'name' => 'Mrs. Teacher Gupta',
            'password' => bcrypt('password'),
            'department_id' => $csDept->id,
            'email_verified_at' => now(),
        ]);

        \App\Models\Teacher::updateOrCreate([
            'email' => 'teacher.cs2@campus.test',
        ],[
            'name' => 'Mr. Teacher Rajpol',
            'password' => bcrypt('password'),
            'department_id' => $csDept->id,
            'email_verified_at' => now(),
        ]);

        \App\Models\Teacher::updateOrCreate([
            'email' => 'teacher.ece@campus.test',
        ],[
            'name' => 'Mrs. Teacher Das',
            'password' => bcrypt('password'),
            'department_id' => $eceDept->id,
            'email_verified_at' => now(),
        ]);

        \App\Models\Teacher::updateOrCreate([
            'email' => 'teacher.me@campus.test',
        ],[
            'name' => 'Mr. Teacher Kumar',
            'password' => bcrypt('password'),
            'department_id' => $meDept->id,
            'email_verified_at' => now(),
        ]);

        // Create or update provider (mark verified)
        \App\Models\Provider::updateOrCreate([
            'email' => 'provider@campus.test',
        ],[
            'name' => 'Supply Provider Ltd.',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Create products
        $products = [
            [
                'name' => 'Notebook A4 (100 pages)',
                'description' => 'Ruled A4 notebook with 100 pages',
                'stock_quantity' => 200,
                'price' => 25.00,
            ],
            [
                'name' => 'Ballpoint Pen (Blue)',
                'description' => 'Blue ballpoint pen - pack of 10',
                'stock_quantity' => 500,
                'price' => 50.00,
            ],
            [
                'name' => 'Ballpoint Pen (Black)',
                'description' => 'Black ballpoint pen - pack of 10',
                'stock_quantity' => 500,
                'price' => 50.00,
            ],
            [
                'name' => 'Pencil Set',
                'description' => 'HB pencils set of 12',
                'stock_quantity' => 150,
                'price' => 30.00,
            ],
            [
                'name' => 'Highlighter Markers',
                'description' => 'Neon colored highlighters - pack of 5',
                'stock_quantity' => 100,
                'price' => 40.00,
            ],
            [
                'name' => 'Correction Fluid',
                'description' => 'White correction fluid 20ml',
                'stock_quantity' => 80,
                'price' => 35.00,
            ],
            [
                'name' => 'Whiteboard Marker Set',
                'description' => 'Colored whiteboard markers - pack of 4',
                'stock_quantity' => 120,
                'price' => 60.00,
            ],
            [
                'name' => 'Sticky Notes Pad',
                'description' => 'Assorted color sticky notes - 100 sheets',
                'stock_quantity' => 200,
                'price' => 20.00,
            ],
            [
                'name' => 'Folder (A4)',
                'description' => 'Plastic folder A4 size',
                'stock_quantity' => 300,
                'price' => 15.00,
            ],
            [
                'name' => 'Stapler',
                'description' => 'Desktop stapler with staples',
                'stock_quantity' => 50,
                'price' => 75.00,
            ],
            [
                'name' => 'Tape Dispenser',
                'description' => 'Scotch tape dispenser',
                'stock_quantity' => 40,
                'price' => 85.00,
            ],
            [
                'name' => 'Paper Clips',
                'description' => 'Steel paper clips - box of 100',
                'stock_quantity' => 150,
                'price' => 10.00,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }

        $this->command->info('✓ Database seeded successfully with:');
        $this->command->info('  - 3 Departments');
        $this->command->info('  - 1 Admin');
        $this->command->info('  - 1 Principal');
        $this->command->info('  - 1 Trust Head');
        $this->command->info('  - 3 HODs');
        $this->command->info('  - 4 Teachers');
        $this->command->info('  - 1 Provider');
        $this->command->info('  - 12 Products');
        $this->command->info('');
        $this->command->info('Test credentials:');
        $this->command->info('  Email: admin@campus.test');
        $this->command->info('  Password: password');
    }
}
