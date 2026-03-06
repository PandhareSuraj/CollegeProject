<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        if (class_exists(\App\Models\Order::class)) {
            // Only show orders that have a vendor connection
            $orders = \App\Models\Order::with('vendor')
                ->whereNotNull('vendor_id')
                ->orderBy('id', 'desc')
                ->paginate(15);
        } else {
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
                'path' => route('admin.orders.index'),
            ]);
        }

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Try to load a real Order model if it exists
        if (class_exists(\App\Models\Order::class)) {
            $order = \App\Models\Order::with('vendor')->find($id);
            if (! $order) {
                return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
            }

            return view('admin.orders.show', compact('order'));
        }

        // If there's no Order model/table yet, show a simple placeholder
        return view('admin.orders.show', ['order' => null]);
    }

    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|string|max:50',
        ]);

        if (! class_exists(\App\Models\Order::class)) {
            return back()->with('error', 'Order model/table not available.');
        }

        $order = \App\Models\Order::find($orderId);
        if (! $order) {
            return back()->with('error', 'Order not found.');
        }

        $order->status = $request->input('status');
        $order->save();

        return back()->with('success', 'Order status updated.');
    }

    public function createBulk(Request $request)
    {
        // Find requests that are sent_to_provider but have no order_id
        $requests = \App\Models\StationaryRequest::where('status', 'sent_to_provider')
            ->whereNull('order_id')
            ->with('items')
            ->get();

        $created = 0;

        foreach ($requests as $req) {
            try {
                $vendor = \App\Models\Vendor::query()->orderBy('id')->first();
                $order = \App\Models\Order::create([
                    'order_number' => 'ORD-' . strtoupper(uniqid()),
                    'vendor_id' => $vendor->id ?? null,
                    'status' => 'pending',
                    'meta' => ['request_id' => $req->id],
                ]);

                $total = 0;
                foreach ($req->items as $ri) {
                    $subtotal = $ri->quantity * $ri->price;
                    \App\Models\OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $ri->product_id,
                        'quantity' => $ri->quantity,
                        'price' => $ri->price,
                        'subtotal' => $subtotal,
                    ]);
                    $total += $subtotal;
                }

                $order->meta = array_merge($order->meta ?? [], ['total_amount' => $total]);
                $order->save();

                if (\Illuminate\Support\Facades\Schema::hasColumn('requests', 'order_id')) {
                    $req->order_id = $order->id;
                    $req->save();
                }

                $created++;
            } catch (\Throwable $e) {
                \Log::warning('Bulk create order failed for request ' . $req->id . ': ' . $e->getMessage());
            }
        }

        return back()->with('success', "Bulk order creation complete. Orders created: $created");
    }

    public function edit($id)
    {
        // Eager-load items and vendor and include an items_count attribute for view summaries
        $order = \App\Models\Order::with('items', 'vendor')->withCount('items')->findOrFail($id);
        $vendors = \App\Models\Vendor::orderBy('name')->get();
        return view('admin.orders.edit', compact('order', 'vendors'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'vendor_id' => 'nullable|exists:vendors,id',
            'status' => 'required|string|max:50',
        ]);

        $order = \App\Models\Order::findOrFail($id);
        $order->vendor_id = $validated['vendor_id'] ?? null;
        $order->status = $validated['status'];
        $order->save();

        return redirect()->route('admin.orders.index')->with('success', 'Order updated');
    }

    public function destroy($id)
    {
        $order = \App\Models\Order::findOrFail($id);
        // detach/cleanup order items first
        $order->items()->delete();
        $order->delete();

        // clear request.order_id if linked
        $req = \App\Models\StationaryRequest::where('order_id', $id)->first();
        if ($req) {
            $req->order_id = null;
            $req->save();
        }

        return back()->with('success', 'Order deleted');
    }
}
