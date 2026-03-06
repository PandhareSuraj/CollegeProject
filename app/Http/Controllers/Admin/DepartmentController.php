<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\College;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::with('college')->paginate(15);
        return view('admin.departments.index', compact('departments'));
    }

    public function create()
    {
    $colleges = College::orderBy('name')->get();
    return view('admin.departments.create', compact('colleges'));
    }

    public function store(Request $request)
    {
        // Accept either an existing college_id or a new_sanstha_name to create and link
        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];

        // If college_id is present, validate it; otherwise require college_id
        if ($request->filled('college_id')) {
            $rules['college_id'] = 'required|exists:colleges,id';
        } else {
            return redirect()->back()->withErrors(['college_id' => 'Please select a college for this department.']);
        }

        $validated = $request->validate($rules);

        Department::create([
            'college_id' => $validated['college_id'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('admin.college-section.department')->with('success','Department created');
    }

    public function show($id)
    {
        // Load related collections for listing, include count attributes and prepare a merged users collection
        $department = Department::with(['college','teachers','hods'])
            ->withCount(['teachers','hods','requests'])
            ->findOrFail($id);

        // Merge teachers and hods into a single collection for the view and normalize ordering
        $departmentUsers = collect([]);
        if ($department->relationLoaded('teachers')) {
            $departmentUsers = $departmentUsers->merge($department->teachers);
        }
        if ($department->relationLoaded('hods')) {
            $departmentUsers = $departmentUsers->merge($department->hods);
        }

        // Ensure a stable order (by name)
        $departmentUsers = $departmentUsers->sortBy('name')->values();

        return view('admin.departments.show', compact('department', 'departmentUsers'));
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.departments.edit', compact('department'));
    }

    public function update(Request $request, $id)
    {
        $department = Department::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $department->update($validated);
        return redirect()->route('admin.departments.index')->with('success','Department updated');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success','Department deleted');
    }
}
