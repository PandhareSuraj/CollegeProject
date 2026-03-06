<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Hod;
use App\Models\Principal;
use App\Models\TrustHead;
use App\Models\Provider;
use App\Models\Administrator;
use App\Models\Department;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Map roles to models
     */
    protected $roleModelMap = [
        'admin' => Administrator::class,
        'teacher' => Teacher::class,
        'hod' => Hod::class,
        'principal' => Principal::class,
        'trust_head' => TrustHead::class,
        'provider' => Provider::class,
    ];

    /**
     * Display a listing of users from all role tables
     */
    public function index()
    {
        $allUsers = [];
        foreach ($this->roleModelMap as $role => $model) {
            $roleUsers = $model::all();
            foreach ($roleUsers as $user) {
                $allUsers[] = $user;
            }
        }

        // Manually paginate the collection
        $perPage = 15;
        $page = request()->get('page', 1);
        $total = count($allUsers);
        $offset = ($page - 1) * $perPage;
        $users = array_slice($allUsers, $offset, $perPage);

        $users = new \Illuminate\Pagination\LengthAwarePaginator(
            $users,
            $total,
            $perPage,
            $page,
            [
                'path' => route('admin.users.index'),
                'query' => request()->query(),
            ]
        );
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        $departments = Department::all();
        $roles = ['admin', 'teacher', 'hod', 'principal', 'trust_head', 'provider'];
        return view('admin.users.create', compact('departments', 'roles'));
    }

    /**
     * Store a newly created user in the appropriate role table
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'mobile_number' => 'required|string|regex:/^[0-9]{10}$/',
            'role' => 'required|in:admin,teacher,hod,principal,trust_head,provider',
        ]);

        // Check email uniqueness across all role tables
        foreach ($this->roleModelMap as $model) {
            if ($model::where('email', $validated['email'])->exists()) {
                return back()->withErrors(['email' => 'This email is already registered.'])->withInput();
            }
        }

        $modelClass = $this->roleModelMap[$validated['role']];
        $modelClass::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'mobile_number' => $validated['mobile_number'],
            'password' => bcrypt($validated['password']),
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user (view only, simplified)
     */
    public function show($id)
    {
        $user = null;
        foreach ($this->roleModelMap as $model) {
            $u = $model::find($id);
            if ($u) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            abort(404, 'User not found');
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user (simplified)
     */
    public function edit($id)
    {
        $user = null;
        foreach ($this->roleModelMap as $model) {
            $u = $model::find($id);
            if ($u) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            abort(404, 'User not found');
        }

        $departments = Department::all();
        $roles = ['admin', 'teacher', 'hod', 'principal', 'trust_head', 'provider'];
        return view('admin.users.edit', compact('user', 'departments', 'roles'));
    }

    /**
     * Update the specified user in storage (simplified)
     */
    public function update(Request $request, $id)
    {
        $user = null;
        foreach ($this->roleModelMap as $model) {
            $u = $model::find($id);
            if ($u) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            abort(404, 'User not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if ($validated['password']) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage (simplified)
     */
    public function destroy($id)
    {
        $user = null;
        foreach ($this->roleModelMap as $model) {
            $u = $model::find($id);
            if ($u) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            abort(404, 'User not found');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}
