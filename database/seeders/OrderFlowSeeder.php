<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\StationaryRequest;
use App\Models\RequestItem;
use App\Models\Approval;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class OrderFlowSeeder extends Seeder
{
    public function run()
    {
        // Create department
        $department = Department::firstOrCreate(['name' => 'Seed Dept']);

        // Create teacher
        $teacher = Teacher::firstOrCreate(
            ['email' => 'teacher@example.test'],
            ['name' => 'Seed Teacher', 'password' => bcrypt('password'), 'department_id' => $department->id]
        );

        // Create vendor
        $vendor = Vendor::firstOrCreate(['name' => 'Seed Vendor'], ['email' => 'vendor@example.test', 'phone' => '0000000000']);

        // Create product
        $product = Product::firstOrCreate(['name' => 'Seed Product'], ['price' => 10.00, 'stock_quantity' => 100]);

        // Create a request
        $request = StationaryRequest::create([
            'department_id' => $department->id,
            'requested_by' => $teacher->id,
            'status' => 'pending',
            'total_amount' => 10.00,
        ]);

        // Add request item
        RequestItem::create([
            'request_id' => $request->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => 10.00,
            'subtotal' => 10.00,
        ]);

        // Simulate approvals: hod, principal, trust_head
        Approval::create(['request_id' => $request->id, 'approved_by' => 1, 'role' => 'hod', 'status' => 'approved']);
        $request->update(['status' => 'hod_approved']);

        Approval::create(['request_id' => $request->id, 'approved_by' => 1, 'role' => 'principal', 'status' => 'approved']);
        $request->update(['status' => 'principal_approved']);

        Approval::create(['request_id' => $request->id, 'approved_by' => 1, 'role' => 'trust_head', 'status' => 'approved']);
        // Now emulate the Trust Head approved flow: send to provider and create order
        $request->update(['status' => 'sent_to_provider']);

        // Create order
        $order = Order::create([
            'order_number' => 'ORD-' . strtoupper(Str::random(6)),
            'vendor_id' => $vendor->id,
            'status' => 'pending',
            'meta' => ['request_id' => $request->id],
        ]);

        // Copy items
        foreach ($request->items as $ri) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $ri->product_id,
                'quantity' => $ri->quantity,
                'price' => $ri->price,
                'subtotal' => $ri->subtotal,
            ]);
        }

        // Link order to request
        if (
            \Schema::hasColumn('requests', 'order_id')
        ) {
            $request->order_id = $order->id;
            $request->save();
        }
    }
}
