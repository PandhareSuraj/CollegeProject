@extends('layouts.app')

@section('title', 'View Department')

@section('content')
<x-theme-container class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="{{ $department->name }}" icon="building-office" />

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Department Details -->
        <div class="theme-card p-6">
            <h2 class="text-xl font-semibold theme-text-primary mb-4">Department Details</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Name</p>
                    <p class="font-semibold theme-text-primary">{{ $department->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Description</p>
                    <p class="theme-text-primary">{{ $department->description ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Created At</p>
                    <p class="font-semibold theme-text-primary">{{ $department->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="theme-card p-6">
            <h2 class="text-xl font-semibold theme-text-primary mb-4">Statistics</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold theme-text-secondary mb-1">Total Teachers</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $department->teachers_count }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary mb-1">Total HODs</p>
                    <p class="text-3xl font-bold text-green-600">{{ $department->hods_count }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary mb-1">Total Requests</p>
                    <p class="text-3xl font-bold text-emerald-600">{{ $department->requests_count }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Department Staff -->
    <div class="theme-card">
        <div class="border-b theme-border-primary px-6 py-4">
            <h2 class="text-xl font-semibold theme-text-primary">Department Staff</h2>
        </div>
        <x-data-table>
            <x-table-header :columns="['Name', 'Email', 'Role']" />
            <tbody>
                @forelse($departmentUsers as $user)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-3 theme-text-primary font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-3 theme-text-secondary">{{ $user->email }}</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <x-empty-state title="No staff in this department" colspan="3" />
                @endforelse
            </tbody>
        </x-data-table>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3">
        <x-action-button 
            href="{{ route('admin.departments.edit', $department->id) }}"
            type="primary"
        >
            Edit Department
        </x-action-button>
        <x-action-button 
            href="{{ route('admin.departments.index') }}"
            type="secondary"
        >
            Back to Departments
        </x-action-button>
    </div>
</x-theme-container>
@endsection
