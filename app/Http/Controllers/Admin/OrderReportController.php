<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Vendor;

class OrderReportController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query()->with('vendor');

        if ($request->isMethod('post')) {
            $from = $request->input('from');
            $to = $request->input('to');
            $vendor = $request->input('vendor');

            if ($from) {
                $query->whereDate('created_at', '>=', $from);
            }
            if ($to) {
                $query->whereDate('created_at', '<=', $to);
            }
            if ($vendor) {
                $query->where('vendor_id', $vendor);
            }
        }

        $orders = $query->orderBy('created_at','desc')->paginate(25);
        $vendors = Vendor::orderBy('name')->get();

        return view('admin.order_reports.index', compact('orders','vendors'));
    }
}
