@extends('layouts.app')

@section('title', 'Departments Management')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
    </svg>
    <h2 class="navbar-title">Departments</h2>
</div>
@endsection

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header 
        title="Departments Management"
        icon="building-office"
    >
        <x-action-button 
            href="{{ route('admin.departments.create') }}"
            type="primary"
        >
            Add Department
        </x-action-button>
    </x-page-header>

    <!-- Departments Table -->
    <x-data-table>
        <x-table-header :columns="['Name', 'Description', 'Teachers', 'HODs', 'Requests', 'Actions']" />
        <tbody>
            @forelse($departments as $department)
                <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                    <td class="px-6 py-4 theme-text-primary font-medium">{{ $department->name }}</td>
                    <td class="px-6 py-4 theme-text-secondary">{{ Str::limit($department->description, 50) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-cyan-100 text-cyan-800">
                            {{ $department->teachers_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            {{ $department->hods_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-200 text-gray-800">
                            {{ $department->requests_count }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <x-table-row-actions 
                            :viewRoute="route('admin.departments.show', $department->id)"
                            :editRoute="route('admin.departments.edit', $department->id)"
                            :deleteRoute="route('admin.departments.destroy', $department->id)"
                        />
                    </td>
                </tr>
            @empty
                <x-empty-state title="No departments found" colspan="6" />
            @endforelse
        </tbody>
    </x-data-table>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $departments->links() }}
    </div>
</x-theme-container>

@endsection
