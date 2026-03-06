<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ ucfirst(str_replace('_', ' ', $role)) }} Login - Campus Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3730a3 30%, #6d28d9 60%, #1e40af 100%);
            background-size: 400% 400%;
            animation: gradientShift 12s ease infinite;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── Centered wrapper ── */
        .login-wrap {
            width: 100%;
            max-width: 440px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        /* ── Card ── */
        .card {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 24px 64px rgba(0,0,0,0.3);
            overflow: hidden;
        }
        html.dark .card {
            background: #0f172a;
            box-shadow: 0 24px 64px rgba(0,0,0,0.6);
        }

        /* ── Card Header ── */
        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #4f46e5 100%);
            padding: 36px 32px 28px;
            text-align: center;
            color: #fff;
        }
        .role-icon {
            width: 64px; height: 64px;
            background: rgba(255,255,255,0.15);
            border-radius: 18px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 16px;
            backdrop-filter: blur(8px);
        }
        .role-icon svg { width: 30px; height: 30px; }
        .card-header h1 {
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }
        .card-header p {
            font-size: 0.82rem;
            opacity: 0.75;
            font-weight: 500;
        }

        /* ── Card Body ── */
        .card-body { padding: 32px; }

        /* ── Alert ── */
        .alert {
            display: flex; gap: 12px; align-items: flex-start;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.875rem;
        }
        .alert svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; }
        .alert-error {
            background: #fef2f2; border: 1px solid #fecaca; color: #991b1b;
        }
        .alert-error svg { color: #dc2626; }
        html.dark .alert-error {
            background: rgba(127,29,29,0.25); border-color: #7f1d1d; color: #fca5a5;
        }
        html.dark .alert-error svg { color: #f87171; }

        /* ── Form ── */
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #374151;
            margin-bottom: 8px;
        }
        html.dark .form-label { color: #94a3b8; }

        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            width: 18px; height: 18px;
            color: #9ca3af;
            pointer-events: none;
        }
        html.dark .input-icon { color: #4b5563; }

        .form-input {
            width: 100%;
            padding: 13px 14px 13px 44px;
            border-radius: 12px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            color: #111827;
            font-size: 0.9rem;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        }
        .form-input::placeholder { color: #9ca3af; }
        .form-input:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.12);
        }
        html.dark .form-input {
            background: #1e293b;
            border-color: #334155;
            color: #f1f5f9;
        }
        html.dark .form-input::placeholder { color: #4b5563; }
        html.dark .form-input:focus {
            border-color: #60a5fa;
            background: #1e293b;
            box-shadow: 0 0 0 3px rgba(96,165,250,0.12);
        }
        .field-error {
            margin-top: 6px;
            font-size: 0.78rem;
            font-weight: 600;
            color: #dc2626;
        }
        html.dark .field-error { color: #f87171; }

        /* ── Remember Me ── */
        .remember-row {
            display: flex; align-items: center; gap: 10px;
            margin-bottom: 24px;
        }
        .remember-row input[type="checkbox"] {
            width: 18px; height: 18px;
            accent-color: #3b82f6;
            cursor: pointer;
            flex-shrink: 0;
        }
        .remember-row label {
            font-size: 0.875rem;
            color: #4b5563;
            cursor: pointer;
            font-weight: 500;
        }
        html.dark .remember-row label { color: #94a3b8; }

        /* ── Submit Button ── */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: #fff;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            border-radius: 12px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: transform 0.18s, box-shadow 0.18s, opacity 0.18s;
            box-shadow: 0 4px 16px rgba(37,99,235,0.35);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(37,99,235,0.45);
        }
        .btn-submit:active { transform: translateY(0); }
        .btn-submit svg { width: 18px; height: 18px; }

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 24px 0;
        }
        html.dark .divider { background: #1e293b; }

        /* ── Footer links ── */
        .footer-links {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }
        .footer-links a {
            font-size: 0.85rem;
            font-weight: 600;
            color: #3b82f6;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px;
            transition: color 0.15s;
        }
        .footer-links a:hover { color: #1d4ed8; }
        .footer-links a.muted {
            color: #6b7280;
            font-weight: 500;
        }
        html.dark .footer-links a { color: #60a5fa; }
        html.dark .footer-links a:hover { color: #93c5fd; }
        html.dark .footer-links a.muted { color: #4b5563; }
        .footer-links a svg { width: 14px; height: 14px; }

        /* ── Credentials box ── */
        .creds-box {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 16px;
            padding: 18px 20px;
        }
        .creds-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: rgba(255,255,255,0.9);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 10px;
            display: flex; align-items: center; gap: 6px;
        }
        .creds-title svg { width: 14px; height: 14px; }
        .creds-code {
            font-family: 'Courier New', monospace;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.85);
            background: rgba(0,0,0,0.2);
            border-radius: 8px;
            padding: 10px 14px;
            line-height: 1.7;
        }
        .creds-code span { color: #93c5fd; font-weight: 700; }
    </style>
</head>
<body>

    <div class="login-wrap">

        {{-- ── LOGIN CARD ── --}}
        <div class="card">

            {{-- Header --}}
            <div class="card-header">
                <div class="role-icon">
                    @switch($role)
                        @case('teacher')
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/></svg>
                            @break
                        @case('hod')
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            @break
                        @case('principal')
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/></svg>
                            @break
                        @case('trust_head')
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                            @break
                        @case('provider')
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4v2l8 5 8-5V4zM4 13v7h16v-7l-8 5-8-5z"/></svg>
                            @break
                        @case('admin')
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a5 5 0 100 10A5 5 0 0012 2zm0 12c-5.33 0-8 2.67-8 4v2h16v-2c0-1.33-2.67-4-8-4z"/></svg>
                            @break
                        @default
                            <svg fill="currentColor" viewBox="0 0 24 24"><path d="M12 2a5 5 0 100 10A5 5 0 0012 2zm0 12c-5.33 0-8 2.67-8 4v2h16v-2c0-1.33-2.67-4-8-4z"/></svg>
                    @endswitch
                </div>
                <h1>{{ ucfirst(str_replace('_', ' ', $role)) }} Login</h1>
                <p>Campus Store Management System</p>
            </div>

            {{-- Body --}}
            <div class="card-body">

                {{-- Validation errors --}}
                @if($errors->any())
                <div class="alert alert-error">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <strong style="display:block;margin-bottom:4px">Login Failed</strong>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="alert alert-error">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>{{ session('error') }}</div>
                </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="{{ route('auth.role-login-submit', $role) }}">
                    @csrf

                    {{-- Email --}}
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-wrap">
                            <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <input type="email" id="email" name="email"
                                   class="form-input"
                                   value="{{ old('email') }}"
                                   placeholder="your.email@campus.test"
                                   required autofocus>
                        </div>
                        @error('email')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Password --}}
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrap">
                            <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            <input type="password" id="password" name="password"
                                   class="form-input"
                                   placeholder="••••••••"
                                   required>
                        </div>
                        @error('password')<p class="field-error">{{ $message }}</p>@enderror
                    </div>

                    {{-- Remember Me --}}
                    <div class="remember-row">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember me for 30 days</label>
                    </div>

                    {{-- Submit --}}
                    <button type="submit" class="btn-submit">
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Login as {{ ucfirst(str_replace('_', ' ', $role)) }}
                    </button>
                </form>

                <div class="divider"></div>

                <div class="footer-links">
                    <a href="{{ route('auth.role-selection') }}">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        Back to Role Selection
                    </a>
                    <a href="{{ route('home') }}" class="muted">Back to Home</a>
                </div>

            </div>
        </div>

        {{-- ── TEST CREDENTIALS (local only) ── --}}
        @if(app()->isLocal())
        <div class="creds-box">
            <div class="creds-title">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
                </svg>
                Test Credentials (Local Only)
            </div>
            <div class="creds-code">
                @switch($role)
                    @case('teacher')    Email: <span>teacher.cs1@campus.test</span><br>Password: <span>password</span> @break
                    @case('hod')        Email: <span>hod.cs@campus.test</span><br>Password: <span>password</span> @break
                    @case('principal')  Email: <span>principal@campus.test</span><br>Password: <span>password</span> @break
                    @case('trust_head') Email: <span>trusthead@campus.test</span><br>Password: <span>password</span> @break
                    @case('provider')   Email: <span>provider@campus.test</span><br>Password: <span>password</span> @break
                    @case('admin')      Email: <span>admin@campus.test</span><br>Password: <span>password</span> @break
                @endswitch
            </div>
        </div>
        @endif

    </div>

    <script>
        (function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            document.getElementById('html-root').classList.toggle('dark', saved ? saved === 'dark' : prefersDark);
        })();
    </script>
</body>
</html>