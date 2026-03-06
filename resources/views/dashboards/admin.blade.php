@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
        </svg>
        Admin Dashboard
    </h1>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $totalRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Total Requests</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-amber-500 dark:text-amber-400">{{ $pendingRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Pending</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-green-600 dark:text-green-400">{{ $approvedRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Approved</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-blue-600 dark:text-blue-400">{{ $completedRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Completed</div>
        </div>
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-red-600 dark:text-red-400">{{ $rejectedRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Rejected</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">₹{{ number_format($totalAmount, 2) }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Total Amount</div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                Quick Actions
            </h2>
        </div>
        <div class="p-6 flex flex-wrap gap-3">
            <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 dark:hover:bg-blue-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.5 1.5H5.75a2.25 2.25 0 00-2.25 2.25v12a2.25 2.25 0 002.25 2.25h8.5a2.25 2.25 0 002.25-2.25V6m-11-4h4v4m0-4l4 4"/>
                </svg>
                Add User
            </a>
            <a href="{{ route('admin.departments.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 dark:hover:bg-blue-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                </svg>
                Add Department
            </a>
            <a href="{{ route('admin.products.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 dark:hover:bg-blue-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                </svg>
                Add Product
            </a>
            <a href="{{ route('requests.index') }}" class="flex items-center gap-2 px-4 py-2 bg-gray-600 text-white hover:bg-gray-700 dark:hover:bg-gray-800 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                View All Requests
            </a>
        </div>
    </div>

    <!-- Status Filter Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('requests.index', ['status' => '']) }}" class="px-4 py-3 border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 font-semibold rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900 dark:hover:bg-opacity-20 transition text-center">View All Requests</a>
        <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="px-4 py-3 border border-amber-600 dark:border-amber-400 text-amber-600 dark:text-amber-400 font-semibold rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900 dark:hover:bg-opacity-20 transition text-center">Pending</a>
        <a href="{{ route('requests.index', ['status' => 'hod_approved']) }}" class="px-4 py-3 border border-green-600 dark:border-green-400 text-green-600 dark:text-green-400 font-semibold rounded-lg hover:bg-green-50 dark:hover:bg-green-900 dark:hover:bg-opacity-20 transition text-center">Approved</a>
        <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="px-4 py-3 border border-blue-600 dark:border-blue-400 text-blue-600 dark:text-blue-400 font-semibold rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900 dark:hover:bg-opacity-20 transition text-center">Completed</a>
    </div>

    <!-- Approved Products -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Approved Products</h2>
        </div>
        <div class="p-6">
            @if(isset($approvedProducts) && count($approvedProducts))
                <div class="space-y-2">
                    @foreach($approvedProducts as $product)
                        <div class="flex justify-between items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <span class="text-gray-900 dark:text-white font-medium">{{ $product->name }}</span>
                            <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300 text-sm font-semibold rounded-full">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500 dark:text-gray-400">No approved products to display.</div>
            @endif
        </div>
    </div>
</div>
@endsection
