@extends('layouts.app')

@section('title', 'Register - Campus Store')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body, .reg-root * { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* ── Pull out of parent padding to use full width ── */
    .content-inner { padding: 0 !important; }

    /* ── Page wrapper ── */
    .reg-root {
        min-height: calc(100vh - 72px);
        background: var(--bg-body, #f0f4ff);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding: 48px 40px 72px;
    }
    html.dark .reg-root { background: #080d1a; }

    .reg-wrap {
        width: 100%;
        max-width: 1140px;
        display: flex;
        flex-direction: column;
        gap: 28px;
    }

    /* ── Card ── */
    .reg-card {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 24px;
        box-shadow: 0 4px 24px rgba(30,64,175,0.08);
        overflow: hidden;
    }
    html.dark .reg-card {
        background: #111827;
        border-color: #1e293b;
        box-shadow: 0 4px 24px rgba(0,0,0,0.4);
    }

    /* ── Card Header ── */
    .reg-header {
        background: linear-gradient(135deg, #1e40af 0%, #4f46e5 100%);
        padding: 40px 48px;
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .reg-header-icon {
        width: 72px; height: 72px;
        background: rgba(255,255,255,0.15);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .reg-header-icon svg { width: 34px; height: 34px; color: #fff; }
    .reg-header h1 {
        font-size: 1.9rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 6px;
        letter-spacing: -0.02em;
    }
    .reg-header p { font-size: 0.9rem; color: rgba(255,255,255,0.7); margin: 0; }

    /* ── Card Body ── */
    .reg-body { padding: 48px; }
    @media (max-width: 600px) { .reg-body { padding: 28px 20px; } }

    /* ── Alert ── */
    .reg-alert {
        display: flex; gap: 12px; align-items: flex-start;
        padding: 14px 16px;
        border-radius: 12px;
        margin-bottom: 28px;
        font-size: 0.875rem;
        background: #fef2f2;
        border: 1px solid #fecaca;
        color: #991b1b;
    }
    .reg-alert svg { width: 18px; height: 18px; flex-shrink: 0; margin-top: 1px; color: #dc2626; }
    html.dark .reg-alert { background: rgba(127,29,29,0.2); border-color: #7f1d1d; color: #fca5a5; }
    html.dark .reg-alert svg { color: #f87171; }
    .reg-alert ul { margin: 6px 0 0; padding-left: 16px; }
    .reg-alert li { margin-bottom: 2px; }

    /* ── Section title ── */
    .sec-title {
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 0.1em;
        text-transform: uppercase;
        color: #94a3b8;
        margin: 0 0 20px;
        display: flex; align-items: center; gap: 10px;
    }
    .sec-title::after { content:''; flex:1; height:1px; background:#e2e8f0; }
    html.dark .sec-title::after { background: #1e293b; }

    /* ── Field grid ── */
    .field-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 20px;
        margin-bottom: 36px;
    }
    .field-grid .full { grid-column: 1 / -1; }
    .field-grid .half { grid-column: span 1; }
    @media (max-width: 800px) { .field-grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 500px) { .field-grid { grid-template-columns: 1fr; } .field-grid .full, .field-grid .half { grid-column: 1; } }

    /* ── Form group ── */
    .fgroup { display: flex; flex-direction: column; gap: 8px; }
    .flabel {
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        color: #475569;
    }
    html.dark .flabel { color: #64748b; }

    .finput-wrap { position: relative; }
    .finput-icon {
        position: absolute;
        left: 15px; top: 50%;
        transform: translateY(-50%);
        width: 18px; height: 18px;
        color: #94a3b8;
        pointer-events: none;
        flex-shrink: 0;
    }
    .finput {
        width: 100%;
        padding: 14px 14px 14px 46px;
        border-radius: 13px;
        border: 1.5px solid #e2e8f0;
        background: #f8fafc;
        color: #0f172a;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.18s, box-shadow 0.18s, background 0.18s;
        font-family: inherit;
    }
    .finput::placeholder { color: #94a3b8; }
    .finput:focus {
        border-color: #3b82f6;
        background: #fff;
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
    }
    .finput.is-invalid { border-color: #f87171; }
    html.dark .finput {
        background: #1e293b; border-color: #334155; color: #f1f5f9;
    }
    html.dark .finput::placeholder { color: #475569; }
    html.dark .finput:focus { border-color: #60a5fa; background: #1e293b; box-shadow: 0 0 0 3px rgba(96,165,250,0.1); }

    select.finput { appearance: none; padding-right: 36px; cursor: pointer; }
    .select-arrow {
        position: absolute;
        right: 12px; top: 50%;
        transform: translateY(-50%);
        width: 16px; height: 16px;
        color: #94a3b8;
        pointer-events: none;
    }

    .field-error { font-size: 0.75rem; color: #dc2626; font-weight: 600; }
    html.dark .field-error { color: #f87171; }
    .field-hint { font-size: 0.75rem; color: #94a3b8; }

    /* ── Role Cards Grid ── */
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 16px;
        margin-bottom: 28px;
    }
    @media (max-width: 900px)  { .roles-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 600px)  { .roles-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 400px)  { .roles-grid { grid-template-columns: 1fr; } }

    .role-item { position: relative; }
    .role-item input[type="radio"] {
        position: absolute;
        opacity: 0;
        width: 0; height: 0;
    }
    .role-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        padding: 28px 20px;
        border-radius: 18px;
        border: 2px solid #e2e8f0;
        background: #f8fafc;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        height: 100%;
        user-select: none;
        position: relative;
        overflow: hidden;
    }
    .role-label::before {
        content: '';
        position: absolute;
        inset: 0;
        opacity: 0;
        transition: opacity 0.2s;
        border-radius: 16px;
    }
    html.dark .role-label { background: #1e293b; border-color: #334155; }
    .role-label:hover {
        border-color: #93c5fd;
        background: #eff6ff;
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(59,130,246,0.14);
    }
    html.dark .role-label:hover { background: #1e3a5f; border-color: #3b82f6; }

    .role-item input:checked + .role-label {
        border-color: #2563eb;
        background: #eff6ff;
        box-shadow: 0 0 0 4px rgba(37,99,235,0.15), 0 8px 24px rgba(37,99,235,0.15);
        transform: translateY(-4px);
    }
    html.dark .role-item input:checked + .role-label {
        background: #1e3a5f;
        border-color: #60a5fa;
        box-shadow: 0 0 0 4px rgba(96,165,250,0.2);
    }

    /* Checkmark badge on selected */
    .role-item input:checked + .role-label::after {
        content: '✓';
        position: absolute;
        top: 10px; right: 12px;
        width: 22px; height: 22px;
        background: #2563eb;
        color: #fff;
        border-radius: 50%;
        font-size: 0.72rem;
        font-weight: 800;
        display: flex; align-items: center; justify-content: center;
        line-height: 22px;
    }
    html.dark .role-item input:checked + .role-label::after { background: #60a5fa; color: #0f172a; }

    .role-icon-box {
        width: 64px; height: 64px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
        transition: transform 0.2s;
    }
    .role-label:hover .role-icon-box { transform: scale(1.08); }
    .role-icon-box svg { width: 28px; height: 28px; }

    .role-name { font-size: 1rem; font-weight: 800; color: #0f172a; }
    html.dark .role-name { color: #f1f5f9; }
    .role-desc { font-size: 0.8rem; color: #64748b; line-height: 1.5; max-width: 140px; }
    html.dark .role-desc { color: #475569; }

    /* ── Dept field ── */
    .dept-field {
        background: #f0f7ff;
        border: 1px solid #bfdbfe;
        border-radius: 14px;
        padding: 16px 18px;
        margin-bottom: 28px;
    }
    html.dark .dept-field { background: #1e3a5f; border-color: #1e40af; }
    .dept-field .flabel { margin-bottom: 8px; display: block; }

    /* ── Actions ── */
    .reg-actions {
        display: flex;
        gap: 12px;
        align-items: center;
    }
    @media (max-width: 480px) { .reg-actions { flex-direction: column; } .reg-actions .btn { width: 100%; justify-content: center; } }

    .btn {
        display: inline-flex; align-items: center; gap: 10px;
        padding: 15px 28px;
        border-radius: 14px;
        font-size: 1rem;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        border: 1.5px solid transparent;
        transition: all 0.18s;
        font-family: inherit;
    }
    .btn svg { width: 19px; height: 19px; flex-shrink: 0; }

    .btn-primary-reg {
        flex: 1;
        justify-content: center;
        background: linear-gradient(135deg, #2563eb, #4f46e5);
        color: #fff;
        border-color: #1d4ed8;
        box-shadow: 0 4px 14px rgba(37,99,235,0.3);
    }
    .btn-primary-reg:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37,99,235,0.4); }

    .btn-ghost {
        background: transparent;
        color: #475569;
        border-color: #e2e8f0;
    }
    .btn-ghost:hover { background: #f1f5f9; border-color: #cbd5e1; color: #0f172a; }
    html.dark .btn-ghost { color: #94a3b8; border-color: #334155; }
    html.dark .btn-ghost:hover { background: #1e293b; color: #f1f5f9; }

    /* ── Login link ── */
    .login-hint {
        text-align: center;
        margin-top: 20px;
        font-size: 0.875rem;
        color: #64748b;
    }
    html.dark .login-hint { color: #475569; }
    .login-hint a { color: #2563eb; font-weight: 700; text-decoration: none; }
    .login-hint a:hover { color: #1d4ed8; text-decoration: underline; }
    html.dark .login-hint a { color: #60a5fa; }

    /* ── Info box ── */
    .info-box {
        background: #fff;
        border: 1px solid #e8edf5;
        border-radius: 14px;
        padding: 16px 20px;
        display: flex; gap: 12px; align-items: flex-start;
        font-size: 0.82rem;
        color: #475569;
        box-shadow: 0 2px 8px rgba(30,64,175,0.05);
    }
    html.dark .info-box { background: #111827; border-color: #1e293b; color: #64748b; }
    .info-box svg { width: 18px; height: 18px; color: #3b82f6; flex-shrink: 0; margin-top: 1px; }
    .info-box strong { color: #1e40af; font-weight: 700; }
    html.dark .info-box strong { color: #60a5fa; }
</style>
@endpush

@section('content')
<div class="reg-root">
    <div class="reg-wrap">

        {{-- ── CARD ── --}}
        <div class="reg-card">

            {{-- Header --}}
            <div class="reg-header">
                <div class="reg-header-icon">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                </div>
                <div>
                    <h1>Create New Account</h1>
                    <p>Select your role and complete registration</p>
                </div>
            </div>

            {{-- Body --}}
            <div class="reg-body">

                {{-- Errors --}}
                @if($errors->any())
                <div class="reg-alert">
                    <svg fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <strong>Please fix the following errors:</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                <form method="POST" action="{{ route('auth.register') }}" novalidate>
                    @csrf

                    {{-- ── Personal Info ── --}}
                    <p class="sec-title">Personal Information</p>
                    <div class="field-grid" style="margin-bottom:36px">

                        {{-- Full Name --}}
                        <div class="fgroup full">
                            <label class="flabel" for="name">Full Name</label>
                            <div class="finput-wrap">
                                <svg class="finput-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                                <input type="text" id="name" name="name" class="finput {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       value="{{ old('name') }}" placeholder="Enter your full name" required autofocus>
                            </div>
                            @error('name')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Email --}}
                        <div class="fgroup half" style="grid-column: span 2">
                            <label class="flabel" for="email">Email Address</label>
                            <div class="finput-wrap">
                                <svg class="finput-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                <input type="email" id="email" name="email" class="finput {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                       value="{{ old('email') }}" placeholder="your.email@campus.test" required>
                            </div>
                            @error('email')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Mobile --}}
                        <div class="fgroup half">
                            <label class="flabel" for="mobile_number">Mobile Number</label>
                            <div class="finput-wrap">
                                <svg class="finput-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                                <input type="text" id="mobile_number" name="mobile_number" class="finput {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}"
                                       value="{{ old('mobile_number') }}" placeholder="10-digit mobile number" maxlength="10" required>
                            </div>
                            @error('mobile_number')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Password --}}
                        <div class="fgroup half">
                            <label class="flabel" for="password">Password</label>
                            <div class="finput-wrap">
                                <svg class="finput-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <input type="password" id="password" name="password" class="finput {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                       placeholder="Minimum 6 characters" required>
                            </div>
                            @error('password')<p class="field-error">{{ $message }}</p>@enderror
                        </div>

                        {{-- Confirm Password --}}
                        <div class="fgroup half">
                            <label class="flabel" for="password_confirmation">Confirm Password</label>
                            <div class="finput-wrap">
                                <svg class="finput-icon" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <input type="password" id="password_confirmation" name="password_confirmation" class="finput"
                                       placeholder="Re-enter password" required>
                            </div>
                        </div>
                    </div>

                    {{-- ── Role Selection ── --}}
                    <p class="sec-title">Select Your Role</p>
                    <div class="roles-grid" style="margin-bottom:8px">

                        @php
                        $roleData = [
                            'teacher'    => ['label'=>'Teacher',    'desc'=>'Create and track requests',   'color'=>'#2563eb','bg'=>'#dbeafe','icon'=>'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z'],
                            'hod'        => ['label'=>'HOD',        'desc'=>'Review & approve requests',   'color'=>'#16a34a','bg'=>'#dcfce7','icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                            'principal'  => ['label'=>'Principal',  'desc'=>'Institutional approvals',    'color'=>'#0891b2','bg'=>'#cffafe','icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                            'trust_head' => ['label'=>'Trust Head', 'desc'=>'Governance oversight',       'color'=>'#d97706','bg'=>'#fef3c7','icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                            'provider'   => ['label'=>'Provider',   'desc'=>'Manage supply orders',       'color'=>'#7c3aed','bg'=>'#ede9fe','icon'=>'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4'],
                            'admin'      => ['label'=>'Admin',      'desc'=>'System administration',      'color'=>'#475569','bg'=>'#f1f5f9','icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        ];
                        @endphp

                        @foreach($roles as $key => $label)
                        @php $rd = $roleData[$key] ?? ['label'=>$label,'desc'=>'','color'=>'#2563eb','bg'=>'#dbeafe','icon'=>'M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z']; @endphp
                        <div class="role-item">
                            <input type="radio" name="role" id="role_{{ $key }}" value="{{ $key }}"
                                   {{ old('role') == $key ? 'checked' : '' }} required>
                            <label class="role-label" for="role_{{ $key }}">
                                <div class="role-icon-box" style="background:{{ $rd['bg'] }}">
                                    <svg fill="none" stroke="{{ $rd['color'] }}" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $rd['icon'] }}"/>
                                    </svg>
                                </div>
                                <span class="role-name">{{ $rd['label'] }}</span>
                                <span class="role-desc">{{ $rd['desc'] }}</span>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    @error('role')<p class="field-error" style="margin-bottom:16px">{{ $message }}</p>@enderror

                    {{-- ── Department (conditional) ── --}}
                    <div id="departmentField" style="display:none">
                        <div class="dept-field">
                            <label class="flabel" for="department_id">Select Department</label>
                            <div class="finput-wrap">
                                <svg class="finput-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <select id="department_id" name="department_id" class="finput {{ $errors->has('department_id') ? 'is-invalid' : '' }}">
                                    <option value="">— Choose a Department —</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="select-arrow" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            @error('department_id')<p class="field-error" style="margin-top:6px">{{ $message }}</p>@enderror
                            <p class="field-hint" style="margin-top:8px">Your department helps organize requests and approvals.</p>
                        </div>
                    </div>

                    {{-- ── Actions ── --}}
                    <div class="reg-actions">
                        <button type="submit" class="btn btn-primary-reg">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Create Account
                        </button>
                        <a href="{{ route('auth.role-selection') }}" class="btn btn-ghost">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                    </div>

                    <p class="login-hint">
                        Already have an account?
                        <a href="{{ route('auth.role-selection') }}">Login here</a>
                    </p>

                </form>
            </div>
        </div>

        {{-- Info box --}}
        <div class="info-box">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span><strong>Registration Info:</strong> Each role is stored in a separate secure table. Your email must be unique across all roles. You can change your role later through admin settings.</span>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('input[name="role"]');
    const deptField = document.getElementById('departmentField');
    const deptSelect = document.getElementById('department_id');

    function toggleDept() {
        const val = document.querySelector('input[name="role"]:checked')?.value;
        const show = val === 'teacher' || val === 'hod';
        deptField.style.display = show ? 'block' : 'none';
        show ? deptSelect.setAttribute('required','required') : deptSelect.removeAttribute('required');
        if (!show) deptSelect.value = '';
    }

    radios.forEach(r => r.addEventListener('change', toggleDept));
    toggleDept();
});
</script>
@endsection