@extends('layouts.app')
<link rel="stylesheet" href="{{ asset('css/app.css') }}">
@section('title', 'Register - Campus Store')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-800 via-indigo-800 to-sky-800 flex items-start justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md">
        <div class="bg-white/95 dark:bg-slate-900/95 rounded-2xl shadow-xl overflow-hidden">
            <div class="px-8 py-10 bg-gradient-to-br from-indigo-600 to-indigo-400">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-white/20 rounded-lg flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-white text-2xl font-extrabold">Create New Account</h1>
                        <p class="text-indigo-100 text-sm mt-1">Select your role and complete registration</p>
                    </div>
                </div>
            </div>

            <div class="px-8 py-8">
                @if($errors->any())
                    <div class="mb-4 p-3 rounded-lg bg-red-50 border border-red-200 text-red-700">
                        <strong class="block font-semibold">Please fix the following errors:</strong>
                        <ul class="mt-2 list-disc list-inside text-sm">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('auth.register') }}" novalidate>
                    @csrf

                    <h2 class="text-xs font-bold uppercase text-slate-500 mb-3">Personal Information</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-semibold text-slate-700">Full Name</label>
                            <input id="name" name="name" value="{{ old('name') }}" required autofocus
                                   class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('name') ? 'ring-2 ring-red-400' : '' }}">
                            @error('name')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-700">Email address</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                   class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('email') ? 'ring-2 ring-red-400' : '' }}">
                            @error('email')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="mobile_number" class="block text-sm font-semibold text-slate-700">Mobile number</label>
                            <input id="mobile_number" name="mobile_number" value="{{ old('mobile_number') }}" maxlength="10" required
                                   class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('mobile_number') ? 'ring-2 ring-red-400' : '' }}">
                            @error('mobile_number')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-semibold text-slate-700">Password</label>
                                <input id="password" name="password" type="password" placeholder="Minimum 6 characters" required
                                       class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 {{ $errors->has('password') ? 'ring-2 ring-red-400' : '' }}">
                                @error('password')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700">Confirm Password</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                       class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            </div>
                        </div>
                    </div>

                    <h2 class="text-xs font-bold uppercase text-slate-500 mt-6 mb-3">Select Your Role</h2>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">

                        @php
                            $restricted = ['principal','trust_head','provider','admin'];
                        @endphp

                        @foreach($roles as $key => $label)
                            @if(in_array($key, $restricted))
                                @continue
                            @endif
                            <label class="flex flex-col items-center p-3 bg-white border rounded-lg cursor-pointer hover:shadow-sm">
                                <input type="radio" name="role" value="{{ $key }}" class="sr-only" {{ old('role') == $key ? 'checked' : '' }} required>
                                <div class="w-12 h-12 rounded-md bg-indigo-50 flex items-center justify-center mb-2">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                    </svg>
                                </div>
                                <span class="text-sm font-bold text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>

                    @error('role')<p class="text-xs text-red-600 mt-2">{{ $message }}</p>@enderror

                    <div id="departmentField" class="mt-4" style="display:none">
                        <label for="department_id" class="block text-sm font-semibold text-slate-700">Select Department</label>
                        <select id="department_id" name="department_id" class="mt-2 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            <option value="">— Choose a Department —</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                        <p class="text-xs text-slate-500 mt-2">Your department helps organize requests and approvals.</p>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center rounded-lg bg-indigo-600 text-white px-4 py-2 font-semibold hover:bg-indigo-700">
                            Create Account
                        </button>
                        <a href="{{ route('auth.role-selection') }}" class="inline-flex items-center justify-center rounded-lg border border-slate-200 px-4 py-2 text-sm text-slate-700 hover:bg-slate-50">Back</a>
                    </div>

                    <p class="text-center text-sm text-slate-500 mt-4">Already have an account? <a href="{{ route('auth.role-selection') }}" class="font-semibold text-indigo-600">Login here</a></p>
                </form>
            </div>
        </div>

        <div class="mt-4 text-sm text-slate-200/80">
            <div class="bg-white/5 rounded-lg p-3 text-slate-700 dark:text-slate-300">
                <strong class="block text-slate-900 dark:text-slate-100">Registration Info:</strong>
                <span class="text-xs text-slate-700 dark:text-slate-300"> Each role is stored in a separate secure table. Your email must be unique across all roles. You can change your role later through admin settings.</span>
            </div>
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
        if (!deptField) return;
        deptField.style.display = show ? 'block' : 'none';
        if (deptSelect) {
            show ? deptSelect.setAttribute('required','required') : deptSelect.removeAttribute('required');
            if (!show) deptSelect.value = '';
        }
    }

    radios.forEach(r => r.addEventListener('change', toggleDept));
    toggleDept();
});
</script>
@endsection