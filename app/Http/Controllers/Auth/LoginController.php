<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Show the role selection page
     */
    public function showRoleSelection()
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.role-selection');
    }

    /**
     * Show role-specific login page
     */
    public function showLoginByRole($role)
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        $validRoles = ['teacher', 'hod', 'principal', 'trust_head', 'provider', 'admin'];
        
        if (!in_array($role, $validRoles)) {
            return redirect()->route('auth.role-selection');
        }

        return view('auth.role-login', compact('role'));
    }

    /**
     * Handle role-specific login
     */
    public function loginByRole(Request $request, $role)
    {
        $validRoles = ['teacher', 'hod', 'principal', 'trust_head', 'provider', 'admin'];

        if (!in_array($role, $validRoles)) {
            return redirect()->route('auth.role-selection');
        }

        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Determine model based on role
        $modelMap = [
            'teacher' => \App\Models\Teacher::class,
            'hod' => \App\Models\Hod::class,
            'principal' => \App\Models\Principal::class,
            'trust_head' => \App\Models\TrustHead::class,
            'provider' => \App\Models\Provider::class,
            'admin' => \App\Models\Administrator::class,
        ];

        $modelClass = $modelMap[$role] ?? null;

        if (!$modelClass) {
            return back()->with('error', 'Invalid role selected.');
        }

        $user = $modelClass::where('email', $validated['email'])->first();

        if (!$user || !Hash::check($validated['password'], $user->password)) {
            return back()->with('error', 'Invalid credentials. Please try again.')->withInput($request->only('email'));
        }

        if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
            return back()->with('error', 'Please verify your email address first.')->withInput($request->only('email'));
        }

        // Login the user into the default guard session
        Auth::login($user);

        // Store the model type and role in session so dashboard routing can pick the correct view
        $request->session()->put('user_model_type', get_class($user));
        $request->session()->put('user_role', $user->role);

        // Regenerate session ID to prevent session fixation attacks (preserves current session data)
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Show generic login page
     */
    public function show()
    {
        // If user is already authenticated, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Handle generic login
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Try to find user across all role tables (check higher roles first)
        $modelMap = [
            \App\Models\Administrator::class,
            \App\Models\Provider::class,
            \App\Models\TrustHead::class,
            \App\Models\Principal::class,
            \App\Models\Hod::class,
            \App\Models\Teacher::class,
        ];

        $user = null;
        foreach ($modelMap as $model) {
            $u = $model::where('email', $validated['email'])->first();
            if ($u && Hash::check($validated['password'], $u->password)) {
                $user = $u;
                break;
            }
        }

        if (!$user) {
            return back()->with('error', 'Invalid credentials.')->withInput($request->only('email'));
        }

        if (method_exists($user, 'hasVerifiedEmail') && !$user->hasVerifiedEmail()) {
            return back()->with('error', 'Please verify your email address first.')->withInput($request->only('email'));
        }

        Auth::login($user);

        // Store the model type and role in session so dashboard routing can pick the correct view
        $request->session()->put('user_model_type', get_class($user));
        $request->session()->put('user_role', $user->role);

        // Regenerate session ID to prevent session fixation attacks (preserves current session data)
        $request->session()->regenerate();

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        
        // Completely invalidate session
        $request->session()->invalidate();
        
        // Regenerate token for CSRF protection
        $request->session()->regenerateToken();
        
        // Flush all session data
        $request->session()->flush();

        return redirect()->route('home');
    }
}
