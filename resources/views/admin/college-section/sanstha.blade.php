@extends('layouts.app')

@section('title', 'Sanstha Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-start mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-1">Sanstha Management</h1>
            <p class="text-gray-600 dark:text-gray-300">Manage all Sansthas in the system</p>
        </div>
        <a href="{{ route('admin.sansthas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Add Sanstha
        </a>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="p-6">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.college-section.sanstha') }}" class="flex gap-3 mb-6">
                <input type="search" name="search" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent" placeholder="Search sansthas..." value="{{ request('search') }}">
                <button type="submit" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-6 rounded-lg transition-colors">Search</button>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-gray-200 bg-gray-50">
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700 w-12">#</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sansthas as $s)
                            <tr class="border-b border-gray-200 hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-3 text-sm text-gray-800">{{ $s->id }}</td>
                                <td class="px-6 py-3 text-sm text-gray-800">{{ $s->name }}</td>
                                <td class="px-6 py-3 text-sm text-gray-600 dark:text-gray-300">{{ Illuminate\Support\Str::limit($s->description, 80) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-gray-500">No sansthas found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $sansthas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
