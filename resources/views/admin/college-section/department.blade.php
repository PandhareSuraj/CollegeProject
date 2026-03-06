@extends('layouts.app')

@section('title', 'Department Management')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Department Management</h1>
            <p class="text-gray-500 text-sm mt-1">Manage all departments in the system</p>
        </div>
        <a href="{{ route('admin.departments.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Department
        </a>
    </div>

    <!-- Search Card -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.college-section.department') }}" class="flex gap-2">
                <input type="search" name="search" class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search departments..." value="{{ request('search') }}">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">Search</button>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 w-16">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">College</th>
                </tr>
            </thead>
            <tbody>
                @forelse($departments as $d)
                    <tr class="border-b border-gray-200 hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-gray-900">{{ $d->id }}</td>
                        <td class="px-6 py-4 text-gray-900">{{ $d->name }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $d->college->name ?? '-' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">No departments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $departments->links() }}
    </div>
</div>
@endsection
