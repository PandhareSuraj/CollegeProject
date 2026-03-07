@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
    </svg>
    <h2 class="navbar-title">Admin Dashboard</h2>
</div>
@endsection

@section('content')
<x-theme-container type="primary" class="px-4 sm:px-6 lg:px-8 py-8">

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-stat-card
            title="Total Requests"
            :value="$totalRequests"
            description="All system requests"
            bgColor="blue"
            icon="<svg class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20'><path d='M4 4a2 2 0 012-2h6a2 2 0 012 2v12a1 1 0 110 2h-7a1 1 0 110-2h7V4z'/></svg>"
        />
        <x-stat-card
            title="Pending Requests"
            :value="$pendingRequests"
            description="Awaiting approval"
            bgColor="amber"
            icon="<svg class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zM7 9a1 1 0 100-2 1 1 0 000 2zm6 0a1 1 0 100-2 1 1 0 000 2z' clip-rule='evenodd'/></svg>"
        />
        <x-stat-card
            title="Approved Requests"
            :value="$approvedRequests"
            description="Successfully approved"
            bgColor="green"
            icon="<svg class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
        />
        <x-stat-card
            title="Completed Requests"
            :value="$completedRequests"
            description="Order delivered"
            bgColor="indigo"
            icon="<svg class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20'><path d='M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z'/></svg>"
        />
    </div>

    <!-- Additional Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <x-stat-card
            title="Rejected Requests"
            :value="$rejectedRequests"
            description="Unsuccessful requests"
            bgColor="red"
            icon="<svg class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z' clip-rule='evenodd'/></svg>"
        />
        <x-stat-card
            title="Total Amount"
            value="₹{{ number_format($totalAmount, 2) }}"
            description="All request values"
            bgColor="purple"
            icon="<svg class='w-6 h-6' fill='currentColor' viewBox='0 0 20 20'><path d='M8.16 5.314l4.897-1.596A1 1 0 0114.25 4.75h1a1 1 0 01.82.439l2.6 3.737a1 1 0 01-.075 1.319l-5.147 3.95.9.6a1 1 0 01.075 1.319l-2.6 3.737A1 1 0 019.25 19.25h-1a1 1 0 01-.82-.439l-2.6-3.737a1 1 0 01.075-1.319l5.147-3.95-.9-.6a1 1 0 01-.075-1.319l2.6-3.737zM5.75 9a1 1 0 011 1v4.5a1 1 0 01-2 0V10a1 1 0 011-1zm-3 3a1 1 0 011 1v1.5a1 1 0 01-2 0V13a1 1 0 011-1z'/></svg>"
        />
    </div>

    <!-- Quick Actions -->
    <x-flux-card class="mb-8">
        <x-slot:header>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 theme-text-secondary" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <h2 class="text-lg font-semibold theme-text-primary">Quick Actions</h2>
            </div>
        </x-slot:header>

        <div class="flex flex-wrap gap-3">
            <x-flux-button variant="primary" size="md">
                <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 3a1 1 0 100 2 5 5 0 015 5 1 1 0 102 0 7 7 0 00-7-7zM0 10a1 1 0 011-1h2a1 1 0 110 2H1a1 1 0 01-1-1z"/>
                    </svg>
                    <span>Add User</span>
                </a>
            </x-flux-button>
            
            <x-flux-button variant="success" size="md">
                <a href="{{ route('admin.departments.create') }}" class="inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>Add Department</span>
                </a>
            </x-flux-button>
            
            <button @class([
                'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors duration-200',
                'focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
                'px-4 py-2 text-base',
                'bg-purple-600 hover:bg-purple-700 dark:bg-purple-700 dark:hover:bg-purple-600 text-white',
            ])>
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                    </svg>
                    <span>Add Product</span>
                </a>
            </button>
            
            <x-flux-button variant="secondary" size="md">
                <a href="{{ route('requests.index') }}" class="inline-flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <span>View All Requests</span>
                </a>
            </x-flux-button>
        </div>
    </x-flux-card>

    <!-- Status Filter Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <a href="{{ route('requests.index', ['status' => '']) }}" class="px-4 py-3 border-2 border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 font-semibold rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900 dark:hover:bg-opacity-20 transition text-center">View All</a>
        <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="px-4 py-3 border-2 border-amber-600 dark:border-amber-500 text-amber-600 dark:text-amber-400 font-semibold rounded-lg hover:bg-amber-50 dark:hover:bg-amber-900 dark:hover:bg-opacity-20 transition text-center">Pending</a>
        <a href="{{ route('requests.index', ['status' => 'hod_approved']) }}" class="px-4 py-3 border-2 border-green-600 dark:border-green-500 text-green-600 dark:text-green-400 font-semibold rounded-lg hover:bg-green-50 dark:hover:bg-green-900 dark:hover:bg-opacity-20 transition text-center">Approved</a>
        <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="px-4 py-3 border-2 border-indigo-600 dark:border-indigo-500 text-indigo-600 dark:text-indigo-400 font-semibold rounded-lg hover:bg-indigo-50 dark:hover:bg-indigo-900 dark:hover:bg-opacity-20 transition text-center">Completed</a>
    </div>

    <!-- Approved Products -->
    <x-flux-card>
        <x-slot:header>
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 theme-text-secondary" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                </svg>
                <h2 class="text-lg font-semibold theme-text-primary">Approved Products</h2>
            </div>
        </x-slot:header>

        @if(isset($approvedProducts) && count($approvedProducts))
            <div class="space-y-2">
                @foreach($approvedProducts as $product)
                    <div class="flex justify-between items-center p-3 theme-border-light border rounded-lg hover:theme-bg-secondary transition">
                        <span class="font-medium theme-text-primary">{{ $product->name }}</span>
                        <x-flux-badge color="blue">₹{{ number_format($product->price, 2) }}</x-flux-badge>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 theme-text-tertiary">No approved products to display.</div>
        @endif
    </x-flux-card>
</x-theme-container>
