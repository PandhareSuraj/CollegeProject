<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account - Campus Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 40px 16px;
            background: linear-gradient(135deg, #0a0f1e 0%, #0f1a35 40%, #1a0a2e 70%, #0a0f1e 100%);
            background-size: 400% 400%;
            animation: gradientShift 14s ease infinite;
            font-family: 'DM Sans', sans-serif;
            color: #f1f5f9;
        }

        @keyframes gradientShift {
            0%   { background-position: 0% 50%; }
            50%  { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* ── Layout ── */
        .page-wrap {
            width: 100%;
            max-width: 860px;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* ── Back link ── */
        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 0.82rem; font-weight: 600;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            transition: color 0.2s;
            padding: 4px 0;
        }
        .back-link:hover { color: #c9a84c; }
        .back-link svg { width: 14px; height: 14px; }

        /* ── Main Card ── */
        .card {
            background: rgba(15,20,40,0.95);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 24px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.5);
            overflow: hidden;
        }

        /* ── Card Header ── */
        .card-header {
            background: linear-gradient(135deg, #1e40af 0%, #4f46e5 50%, #6d28d9 100%);
            padding: 36px 44px 32px;
            display: flex; align-items: center; gap: 20px;
            position: relative; overflow: hidden;
        }
        .card-header::after {
            content: '';
            position: absolute; top: -40px; right: -40px;
            width: 160px; height: 160px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
        }
        .card-header::before {
            content: '';
            position: absolute; bottom: -60px; right: 80px;
            width: 200px; height: 200px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }
        .header-icon {
            width: 60px; height: 60px;
            background: rgba(255,255,255,0.15);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
        }
        .header-icon svg { width: 28px; height: 28px; color: #fff; }
        .header-text h1 {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem; font-weight: 900;
            color: #fff; letter-spacing: -0.01em;
            margin-bottom: 4px;
        }
        .header-text p {
            font-size: 0.85rem; color: rgba(255,255,255,0.65);
            font-weight: 400;
        }

        /* ── Card Body ── */
        .card-body {
            padding: 36px 44px 40px;
        }

        /* ── Error Alert ── */
        .alert-error {
            display: flex; gap: 12px; align-items: flex-start;
            padding: 14px 16px;
            border-radius: 12px;
            margin-bottom: 28px;
            background: rgba(127,29,29,0.3);
            border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5;
            font-size: 0.875rem;
        }
        .alert-error svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; color: #f87171; }
        .alert-error strong { display: block; margin-bottom: 6px; color: #fecaca; font-weight: 700; }

        /* ── Section Labels ── */
        .section-label {
            font-size: 0.68rem;
            font-weight: 800;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: rgba(201,168,76,0.8);
            margin-bottom: 16px;
            display: flex; align-items: center; gap: 10px;
        }
        .section-label::after {
            content: '';
            flex: 1; height: 1px;
            background: rgba(255,255,255,0.07);
        }

        /* ── Two-column grid for fields ── */
        .fields-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 28px;
        }
        .fields-grid .full-width { grid-column: 1 / -1; }

        /* ── Form Groups ── */
        .form-group { display: flex; flex-direction: column; }
        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #94a3b8;
            margin-bottom: 8px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 13px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: #4b5563;
            pointer-events: none;
        }
        .form-input {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border-radius: 10px;
            border: 1.5px solid rgba(255,255,255,0.09);
            background: rgba(255,255,255,0.05);
            color: #f1f5f9;
            font-size: 0.875rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }
        .form-input::placeholder { color: #4b5563; }
        .form-input:focus {
            border-color: #4f46e5;
            background: rgba(79,70,229,0.08);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
        }
        .form-input.has-error {
            border-color: rgba(239,68,68,0.5);
        }
        .field-error {
            margin-top: 5px;
            font-size: 0.73rem;
            font-weight: 600;
            color: #f87171;
        }

        /* ── Password (no icon needed, full width) ── */
        .form-input.no-icon { padding-left: 14px; }

        /* ── Role Selection ── */
        .roles-section { margin-bottom: 28px; }
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .role-label {
            position: relative; cursor: pointer;
        }
        .role-label input[type="radio"] {
            position: absolute; opacity: 0; width: 0; height: 0;
        }
        .role-card {
            display: flex; align-items: center; gap: 14px;
            padding: 14px 18px;
            border-radius: 12px;
            border: 1.5px solid rgba(255,255,255,0.08);
            background: rgba(255,255,255,0.03);
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }
        .role-label input:checked + .role-card {
            border-color: #4f46e5;
            background: rgba(79,70,229,0.12);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
        }
        .role-label:hover .role-card {
            border-color: rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.06);
        }
        .role-card-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            background: rgba(79,70,229,0.15);
        }
        .role-label input:checked + .role-card .role-card-icon {
            background: rgba(79,70,229,0.3);
        }
        .role-card-icon svg { width: 20px; height: 20px; color: #a5b4fc; }
        .role-card-info .role-name {
            font-size: 0.875rem; font-weight: 700; color: #fff;
            display: block;
        }
        .role-card-info .role-sub {
            font-size: 0.73rem; color: #64748b;
            display: block; margin-top: 1px;
        }
        .role-label input:checked + .role-card .role-card-info .role-sub {
            color: #818cf8;
        }
        /* Radio indicator */
        .role-radio-dot {
            margin-left: auto; flex-shrink: 0;
            width: 18px; height: 18px; border-radius: 50%;
            border: 2px solid rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            transition: border-color 0.18s;
        }
        .role-radio-dot::after {
            content: '';
            width: 8px; height: 8px; border-radius: 50%;
            background: #4f46e5;
            transform: scale(0); transition: transform 0.18s;
        }
        .role-label input:checked + .role-card .role-radio-dot {
            border-color: #4f46e5;
        }
        .role-label input:checked + .role-card .role-radio-dot::after {
            transform: scale(1);
        }

        /* ── Department Field ── */
        .dept-field {
            margin-bottom: 28px;
            display: none;
        }
        .dept-field.visible { display: block; }
        .form-select {
            width: 100%;
            padding: 11px 14px 11px 40px;
            border-radius: 10px;
            border: 1.5px solid rgba(255,255,255,0.09);
            background: rgba(255,255,255,0.05);
            color: #f1f5f9;
            font-size: 0.875rem;
            font-family: 'DM Sans', sans-serif;
            outline: none;
            cursor: pointer;
            appearance: none;
            transition: border-color 0.18s, background 0.18s, box-shadow 0.18s;
        }
        .form-select:focus {
            border-color: #4f46e5;
            background: rgba(79,70,229,0.08);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
        }
        .form-select option { background: #0f172a; color: #f1f5f9; }
        .select-arrow {
            position: absolute; right: 13px; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px; color: #4b5563;
            pointer-events: none;
        }

        /* ── Actions ── */
        .form-actions {
            display: flex; gap: 12px; align-items: center;
            margin-bottom: 20px;
        }
        .btn-submit {
            flex: 1;
            padding: 13px 24px;
            background: linear-gradient(135deg, #1d4ed8, #4f46e5);
            color: #fff; font-size: 0.925rem; font-weight: 700;
            border: none; border-radius: 11px; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            font-family: 'DM Sans', sans-serif;
            transition: transform 0.18s, box-shadow 0.18s;
            box-shadow: 0 4px 18px rgba(79,70,229,0.35);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 28px rgba(79,70,229,0.5);
        }
        .btn-submit svg { width: 16px; height: 16px; }
        .btn-back {
            padding: 13px 22px;
            border-radius: 11px;
            border: 1.5px solid rgba(255,255,255,0.12);
            background: rgba(255,255,255,0.04);
            color: #94a3b8; font-size: 0.875rem; font-weight: 600;
            text-decoration: none; cursor: pointer;
            font-family: 'DM Sans', sans-serif;
            transition: border-color 0.18s, color 0.18s;
            white-space: nowrap;
        }
        .btn-back:hover { border-color: rgba(255,255,255,0.25); color: #fff; }

        /* ── Login link ── */
        .login-link {
            text-align: center;
            font-size: 0.85rem;
            color: #475569;
        }
        .login-link a {
            color: #818cf8; font-weight: 600;
            text-decoration: none;
            transition: color 0.15s;
        }
        .login-link a:hover { color: #a5b4fc; }

        /* ── Info box below card ── */
        .info-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 14px;
            padding: 16px 20px;
            display: flex; gap: 12px; align-items: flex-start;
        }
        .info-box svg { width: 16px; height: 16px; color: #c9a84c; flex-shrink: 0; margin-top: 1px; }
        .info-box p { font-size: 0.80rem; color: #475569; line-height: 1.6; }
        .info-box strong { color: #94a3b8; }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .card-header { padding: 28px 24px 24px; }
            .card-body { padding: 28px 24px 32px; }
            .fields-grid { grid-template-columns: 1fr; }
            .roles-grid { grid-template-columns: 1fr; }
            .form-actions { flex-direction: column; }
            .btn-back { width: 100%; text-align: center; }
        }
    </style>
</head>
<body>

    <div class="page-wrap">

        <!-- Back link -->
        <a href="{{ route('home') }}" class="back-link">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
            </svg>
            Back to Home
        </a>

        <!-- Main Card -->
        <div class="card">

            <!-- Header -->
            <div class="card-header">
                <div class="header-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div class="header-text">
                    <h1>Create New Account</h1>
                    <p>Select your role and complete registration to access Campus Store</p>
                </div>
            </div>

            <!-- Body -->
            <div class="card-body">

                @if($errors->any())
                    <div class="alert-error">
                        <svg fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <strong>Please fix the following errors:</strong>
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.register') }}" novalidate>
                    @csrf

                    <!-- Personal Information -->
                    <p class="section-label">Personal Information</p>

                    <div class="fields-grid">

                        <!-- Full Name -->
                        <div class="form-group full-width">
                            <label class="form-label" for="name">Full Name</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <input id="name" name="name" type="text"
                                       value="{{ old('name') }}" required autofocus
                                       placeholder="Enter your full name"
                                       class="form-input {{ $errors->has('name') ? 'has-error' : '' }}">
                            </div>
                            @error('name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <input id="email" name="email" type="email"
                                       value="{{ old('email') }}" required
                                       placeholder="your@email.com"
                                       class="form-input {{ $errors->has('email') ? 'has-error' : '' }}">
                            </div>
                            @error('email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Mobile -->
                        <div class="form-group">
                            <label class="form-label" for="mobile_number">Mobile Number</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <input id="mobile_number" name="mobile_number" type="text"
                                       value="{{ old('mobile_number') }}" maxlength="10" required
                                       placeholder="10-digit number"
                                       class="form-input {{ $errors->has('mobile_number') ? 'has-error' : '' }}">
                            </div>
                            @error('mobile_number')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Password -->
                        <div class="form-group">
                            <label class="form-label" for="password">Password</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <input id="password" name="password" type="password" required
                                       placeholder="Min. 6 characters"
                                       class="form-input {{ $errors->has('password') ? 'has-error' : '' }}">
                            </div>
                            @error('password')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirm Password</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       placeholder="Re-enter password"
                                       class="form-input">
                            </div>
                        </div>

                    </div>

                    <!-- Role Selection -->
                    <div class="roles-section">
                        <p class="section-label">Select Your Role</p>

                        @php
                            $restricted = ['principal','trust_head','provider','admin'];
                            $roleIcons = [
                                'teacher' => ['sub' => 'Create & track requests', 'color' => 'rgba(34,197,94,0.15)', 'iconColor' => '#4ade80'],
                                'hod'     => ['sub' => 'Review & approve requests', 'color' => 'rgba(99,102,241,0.15)', 'iconColor' => '#a5b4fc'],
                            ];
                        @endphp

                        <div class="roles-grid">
                            @foreach($roles as $key => $label)
                                @if(in_array($key, $restricted)) @continue @endif
                                <label class="role-label">
                                    <input type="radio" name="role" value="{{ $key }}"
                                           {{ old('role') == $key ? 'checked' : '' }} required>
                                    <div class="role-card">
                                        <div class="role-card-icon" style="background: {{ $roleIcons[$key]['color'] ?? 'rgba(79,70,229,0.15)' }}">
                                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                                                 style="color: {{ $roleIcons[$key]['iconColor'] ?? '#a5b4fc' }}">
                                                @if($key === 'teacher')
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                                @elseif($key === 'hod')
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                @endif
                                            </svg>
                                        </div>
                                        <div class="role-card-info">
                                            <span class="role-name">{{ $label }}</span>
                                            <span class="role-sub">{{ $roleIcons[$key]['sub'] ?? '' }}</span>
                                        </div>
                                        <div class="role-radio-dot"></div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        @error('role')<p class="field-error" style="margin-top:8px;">{{ $message }}</p>@enderror
                    </div>

                    <!-- Department Field -->
                    <div class="dept-field" id="departmentField">
                        <p class="section-label">Department</p>
                        <div class="form-group">
                            <label class="form-label" for="department_id">Select Department</label>
                            <div class="input-wrap">
                                <svg class="input-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                                </svg>
                                <select id="department_id" name="department_id" class="form-select">
                                    <option value="">— Choose a Department —</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="select-arrow" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            @error('department_id')<p class="field-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="form-actions">
                        <button type="submit" class="btn-submit">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Create Account
                        </button>
                        <a href="{{ route('home') }}" class="btn-back">← Back</a>
                    </div>

                    <p class="login-link">
                        Already have an account?
                        <a href="{{ route('home') }}">Login here</a>
                    </p>

                </form>
            </div>
        </div>

        <!-- Info box -->
        <div class="info-box">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 8a6 6 0 01-7.743 5.743L10 14l-1 1-1 1H6v2H2v-4l4.257-4.257A6 6 0 1118 8zm-6-4a1 1 0 100 2 2 2 0 012 2 1 1 0 102 0 4 4 0 00-4-4z" clip-rule="evenodd"/>
            </svg>
            <p>
                <strong>Registration Info:</strong>
                Each role is stored in a separate secure table. Your email must be unique across all roles.
                You can update your details later through admin settings.
            </p>
        </div>

    </div>

    <script>
        // Department field toggle
        (function() {
            var radios   = document.querySelectorAll('input[name="role"]');
            var deptField = document.getElementById('departmentField');
            var deptSelect = document.getElementById('department_id');

            function toggleDept() {
                var val = document.querySelector('input[name="role"]:checked')?.value;
                var show = (val === 'teacher' || val === 'hod');
                deptField.classList.toggle('visible', show);
                if (deptSelect) {
                    show ? deptSelect.setAttribute('required','required')
                         : deptSelect.removeAttribute('required');
                    if (!show) deptSelect.value = '';
                }
            }

            radios.forEach(function(r) { r.addEventListener('change', toggleDept); });
            toggleDept();
        })();
    </script>
</body>
</html>