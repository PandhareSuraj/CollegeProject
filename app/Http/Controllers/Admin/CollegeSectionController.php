<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sanstha;
use App\Models\College;
use App\Models\Department;
use App\Models\Teacher;
use App\Models\User;

class CollegeSectionController extends Controller
{
    /**
     * Show Sanstha management page
     */
    public function sanstha(Request $request)
    {
        $query = Sanstha::query();
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
        }
        
        $sansthas = $query->paginate(15);
        
        return view('admin.college-section.sanstha', compact('sansthas'));
    }

    /**
     * Show College management page
     */
    public function college(Request $request)
    {
        $query = College::with('sanstha');
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
        }
        
        $colleges = $query->paginate(15);
        
        return view('admin.college-section.college', compact('colleges'));
    }

    /**
     * Show Department management page
     */
    public function department(Request $request)
    {
        $sansthas = Sanstha::with('colleges.departments')->orderBy('name')->get();
        
        $query = Department::with(['college', 'college.sanstha']);
        
        // Filter by Sanstha if provided
        if ($request->filled('sanstha_id')) {
            $query->whereHas('college', function($q) {
                $q->where('sanstha_id', request('sanstha_id'));
            });
        }
        
        // Filter by College if provided
        if ($request->filled('college_id')) {
            $query->where('college_id', $request->input('college_id'));
        }
        
        // Search by department name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
        
        $departments = $query->paginate(15);
        
        return view('admin.college-section.department', compact('departments', 'sansthas'));
    }

    /**
     * Show Department Users management page
     */
    public function departmentUsers(Request $request)
    {
        $sansthas = Sanstha::with('colleges.departments')->orderBy('name')->get();
        $allDepartments = Department::with('college.sanstha')->orderBy('name')->get();
        $departmentUsers = [];
        
        if ($request->filled('department_id')) {
            $department = Department::with(['teachers', 'hods'])->find($request->get('department_id'));
            
            if ($department) {
                $teachers = $department->teachers()->get();
                $hods = $department->hods()->get();
                $departmentUsers = $teachers->merge($hods)->sortByDesc('created_at');
            }
        }
        
        return view('admin.college-section.department-users', compact('departmentUsers', 'allDepartments', 'sansthas'));
    }

    /**
     * Legacy method - redirect to sanstha page
     */
    public function index(Request $request)
    {
        return redirect()->route('admin.college-section.sanstha');
    }
}
