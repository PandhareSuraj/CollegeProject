@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<x-theme-container class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Create New User" icon="user-plus" />

    <div class="theme-card p-6">
        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf

            <x-form-input 
                name="name"
                label="Name"
                required
                placeholder="Enter full name"
            />

            <x-form-input 
                name="email"
                label="Email"
                type="email"
                required
                placeholder="user@example.com"
            />

            <x-form-select 
                name="role"
                label="Role"
                required
            >
                <option value="">Select Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role }}" {{ old('role') == $role ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $role)) }}
                    </option>
                @endforeach
            </x-form-select>

            <div>
                <label for="department_id" class="block text-sm font-semibold theme-text-primary mb-2">Department</label>
                <p class="text-xs theme-text-secondary mb-2">Required for Teacher and HOD roles</p>
                <select id="department_id" name="department_id"
                        class="w-full border theme-border-primary rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 theme-bg-primary theme-text-primary @error('department_id') border-red-500 @enderror">
                    <option value="">Select Department</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <x-form-input 
                name="password"
                label="Password"
                type="password"
                required
                placeholder="Enter password"
            />

            <x-form-input 
                name="password_confirmation"
                label="Confirm Password"
                type="password"
                required
                placeholder="Confirm password"
            />

            <div class="flex gap-3 pt-4">
                <x-form-button type="submit" variant="primary">
                    Create User
                </x-form-button>
                <x-action-button 
                    href="{{ route('admin.users.index') }}"
                    type="secondary"
                >
                    Cancel
                </x-action-button>
            </div>
        </form>
    </div>
</x-theme-container>
@endsection
