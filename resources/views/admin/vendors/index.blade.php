@extends('layouts.app')

@section('title','Vendors')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Vendors</h1>
        <a href="{{ route('admin.vendors.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition font-medium">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Vendor
        </a>
    </div>

    <!-- Vendors Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 w-12">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Name</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Company</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Phone</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Email</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($vendors as $v)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 text-gray-900">{{ $v->id }}</td>
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ $v->name }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $v->company_name }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $v->phone }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">{{ $v->email }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('admin.vendors.edit', $v->id) }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Edit
                            </a>
                            <form action="{{ route('admin.vendors.destroy', $v->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 text-sm bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition font-medium" onclick="return confirm('Delete this vendor?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $vendors->links() }}
    </div>
</div>
@endsection
