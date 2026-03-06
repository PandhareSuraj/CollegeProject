<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        // If the vendors table doesn't exist (migrations not run), return an empty paginator and a helpful message
        if (!Schema::hasTable('vendors')) {
            $vendors = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15, 1, [
                'path' => route('admin.vendors.index'),
            ]);

            return view('admin.vendors.index', compact('vendors'))
                ->with('error', 'Database table "vendors" does not exist. Run: php artisan migrate');
        }

    $vendors = Vendor::paginate(15);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {
        return view('admin.vendors.create');
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('vendors')) {
            return back()->with('error', 'Database table "vendors" does not exist. Run: php artisan migrate');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string',
        ]);

    Vendor::create($validated);
        return redirect()->route('admin.vendors.index')->with('success','Vendor created');
    }

    public function edit($id)
    {
        if (!Schema::hasTable('vendors')) {
            return redirect()->route('admin.vendors.index')->with('error', 'Database table "vendors" does not exist. Run: php artisan migrate');
        }

        $vendor = Vendor::findOrFail($id);
        return view('admin.vendors.edit', compact('vendor'));
    }

    public function update(Request $request, $id)
    {
        if (!Schema::hasTable('vendors')) {
            return back()->with('error', 'Database table "vendors" does not exist. Run: php artisan migrate');
        }

        $vendor = Vendor::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'address' => 'nullable|string',
            'gst_number' => 'nullable|string',
        ]);

        $vendor->update($validated);
        return redirect()->route('admin.vendors.index')->with('success','Vendor updated');
    }

    public function destroy($id)
    {
        if (!Schema::hasTable('vendors')) {
            return redirect()->route('admin.vendors.index')->with('error', 'Database table "vendors" does not exist. Run: php artisan migrate');
        }

        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        return redirect()->route('admin.vendors.index')->with('success','Vendor deleted');
    }
}
