@extends('layouts.app')

@section('title', 'Department Users Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Department Users Management</h1>
            <p class="text-gray-500 text-sm mt-1">Manage users assigned to departments</p>
        </div>
    </div>

    <!-- Search and Filter Card -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.college-section.department-users') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Department</label>
                    <select name="department_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                        <option value="">-- Select Department --</option>
                        @foreach($allDepartments as $d)
                            <option value="{{ $d->id }}" @if(request('department_id') == $d->id) selected @endif>
                                {{ $d->name }} ({{ $d->college->name ?? 'N/A' }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition font-medium">Load Users</button>
            </form>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 w-16">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Role</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departmentUsers as $u)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-900">{{ $u->id }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $u->name }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $u->email ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($u->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.users.edit', $u) }}" class="inline-flex items-center px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition text-sm font-medium">
                                Edit
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            @if(request('department_id'))
                                No users found for this department.
                            @else
                                Select a department to view users.
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
