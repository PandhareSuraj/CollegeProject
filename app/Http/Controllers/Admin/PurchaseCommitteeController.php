<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PurchaseCommitteeController extends Controller
{
    public function index()
    {
        $committees = \App\Models\PurchaseCommittee::paginate(15);
        return view('admin.purchase_committees.index', compact('committees'));
    }

    public function create()
    {
        return view('admin.purchase_committees.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        \App\Models\PurchaseCommittee::create($validated);
        return redirect()->route('admin.purchase-committees.index')->with('success','Committee created');
    }

    public function edit($id)
    {
        $committee = \App\Models\PurchaseCommittee::findOrFail($id);
        return view('admin.purchase_committees.edit', compact('committee'));
    }

    public function update(Request $request, $id)
    {
        $committee = \App\Models\PurchaseCommittee::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $committee->update($validated);
        return redirect()->route('admin.purchase-committees.index')->with('success','Committee updated');
    }

    public function destroy($id)
    {
        $committee = \App\Models\PurchaseCommittee::findOrFail($id);
        $committee->delete();
        return redirect()->route('admin.purchase-committees.index')->with('success','Committee deleted');
    }
}
