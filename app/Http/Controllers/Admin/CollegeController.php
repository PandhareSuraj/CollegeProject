<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\College;

class CollegeController extends Controller
{
    public function create()
    {
        return view('admin.colleges.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sanstha_id' => 'required|exists:sansthas,id',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        College::create($validated);
        return redirect()->route('admin.college-section.college')->with('success','College created');
    }
}
