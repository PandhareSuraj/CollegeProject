@extends('layouts.app')

@section('title', 'Department Users Management')

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Department Users Management" icon="users">
        <p class="text-sm theme-text-secondary mt-1">Manage users assigned to departments</p>
    </x-page-header>

    <!-- Search and Filter Card -->
    <div class="theme-card mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.college-section.department-users') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium theme-text-primary mb-2">Sanstha</label>
                        <select name="sanstha_id" class="w-full border theme-border-primary rounded-lg px-4 py-2 text-sm theme-bg-primary theme-text-primary focus:outline-none focus:ring-2 focus:ring-blue-600" id="sansthaSelect" onchange="updateColleges()">
                            <option value="">-- All Sansthas --</option>
                            @foreach($sansthas as $sanstha)
                                <option value="{{ $sanstha->id }}" @if(request('sanstha_id') == $sanstha->id) selected @endif>
                                    {{ $sanstha->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium theme-text-primary mb-2">Select Department</label>
                        <select name="department_id" class="w-full border theme-border-primary rounded-lg px-4 py-2 text-sm theme-bg-primary theme-text-primary focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">-- Select Department --</option>
                            @foreach($allDepartments as $d)
                                <option value="{{ $d->id }}" @if(request('department_id') == $d->id) selected @endif>
                                    {{ $d->name }} ({{ optional($d->college)->name ?? 'N/A' }}) - {{ optional(optional($d->college)->sanstha)->name ?? 'N/A' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition font-medium rounded-lg">Load Users</button>
                    <a href="{{ route('admin.college-section.department-users') }}" class="px-6 py-2 theme-bg-secondary theme-text-primary rounded-lg hover:theme-bg-secondary transition font-medium">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="theme-card overflow-x-auto">
        <x-data-table>
            <x-table-header :columns="['#', 'Name', 'Email', 'Role', 'Actions']" />
            <tbody>
                @forelse($departmentUsers as $u)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-4 font-semibold theme-text-primary">{{ $u->id }}</td>
                        <td class="px-6 py-4 theme-text-primary">{{ $u->name }}</td>
                        <td class="px-6 py-4 theme-text-secondary">{{ $u->email ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                {{ ucfirst(str_replace('_', ' ', $u->role)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <x-action-button href="{{ route('admin.users.edit', $u) }}" type="secondary" class="text-sm">Edit</x-action-button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center theme-text-secondary">
                            @if(request('department_id'))
                                No users found for this department.
                            @else
                                Select a department to view users.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </x-data-table>
    </div>
</x-theme-container>
@endsection
