<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Show registration form
     */
    public function showRegistrationForm()
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        $roles = ['teacher' => 'Teacher', 'hod' => 'Head of Department', 'principal' => 'Principal', 'trust_head' => 'Trust Head', 'provider' => 'Provider', 'admin' => 'Administrator'];
        $departments = \App\Models\Department::all();

        return view('auth.register', compact('roles', 'departments'));
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validRoles = ['teacher', 'hod', 'principal', 'trust_head', 'provider', 'admin'];

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'mobile_number' => ['required', 'string', 'regex:/^[0-9]{10}$/', 'unique:teachers,mobile_number', 'unique:hods,mobile_number', 'unique:principals,mobile_number', 'unique:trust_heads,mobile_number', 'unique:providers,mobile_number', 'unique:administrators,mobile_number'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'in:' . implode(',', $validRoles)],
        ];

        // Add department_id validation for teacher and hod
        if ($request->input('role') === 'teacher' || $request->input('role') === 'hod') {
            $rules['department_id'] = ['required', 'exists:departments,id'];
        }

        $data = $request->validate($rules, [
            'mobile_number.regex' => 'Mobile number must be 10 digits.',
            'mobile_number.unique' => 'This mobile number is already registered.',
            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'The selected department does not exist.',
        ]);

        // Ensure email is not used in any role table
        $modelsToCheck = [
            \App\Models\Teacher::class,
            \App\Models\Hod::class,
            \App\Models\Principal::class,
            \App\Models\TrustHead::class,
            \App\Models\Provider::class,
            \App\Models\Administrator::class,
        ];

        foreach ($modelsToCheck as $m) {
            if ($m::where('email', $data['email'])->exists()) {
                return back()->withErrors(['email' => 'This email is already registered in another role.'])->withInput();
            }
        }

        $modelMap = [
            'teacher' => \App\Models\Teacher::class,
            'hod' => \App\Models\Hod::class,
            'principal' => \App\Models\Principal::class,
            'trust_head' => \App\Models\TrustHead::class,
            'provider' => \App\Models\Provider::class,
            'admin' => \App\Models\Administrator::class,
        ];

        $modelClass = $modelMap[$data['role']];

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'mobile_number' => $data['mobile_number'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => app()->isLocal() ? now() : null,
        ];

        // Add department_id if role is teacher or hod
        if (($data['role'] === 'teacher' || $data['role'] === 'hod') && isset($data['department_id'])) {
            $userData['department_id'] = $data['department_id'];
        }

        $user = $modelClass::create($userData);

        Auth::login($user);

        // Store the model type and role in session so dashboard routing can pick the correct view
        $request->session()->put('user_model_type', get_class($user));
        $request->session()->put('user_role', $user->role);

        // Regenerate session ID to prevent session fixation attacks
        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
}
