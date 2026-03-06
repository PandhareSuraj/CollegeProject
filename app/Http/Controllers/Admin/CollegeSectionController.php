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
        $query = Department::with('college');
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
        
        $departments = $query->paginate(15);
        
        return view('admin.college-section.department', compact('departments'));
    }

    /**
     * Show Department Users management page
     */
    public function departmentUsers(Request $request)
    {
        $allDepartments = Department::with('college')->orderBy('name')->get();
        $departmentUsers = [];
        
        if ($request->filled('department_id')) {
            $department = Department::with('teachers')->find($request->get('department_id'));
            $departmentUsers = $department ? $department->teachers()->get() : [];
        }
        
        return view('admin.college-section.department-users', compact('departmentUsers', 'allDepartments'));
    }

    /**
     * Legacy method - redirect to sanstha page
     */
    public function index(Request $request)
    {
        return redirect()->route('admin.college-section.sanstha');
    }
}
