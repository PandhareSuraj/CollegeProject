@extends('layouts.app')

@section('title','Orders')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
    </svg>
    <h2 class="navbar-title">Orders</h2>
</div>
@endsection

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Orders" />

    <!-- Create Bulk Orders Button -->
    <div class="mb-6">
        <form method="POST" action="{{ route('admin.orders.create-bulk') }}" class="inline">
            @csrf
            <x-action-button type="primary">
                Create All Missing Orders
            </x-action-button>
        </form>
    </div>

    <x-data-table>
        <x-table-header :columns="['#', 'Order No', 'Vendor', 'Status', 'Actions']" />
        <tbody>
            @forelse($orders as $o)
                <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                    <td class="px-6 py-4 theme-text-primary">{{ $o->id }}</td>
                    <td class="px-6 py-4 theme-text-primary font-medium">{{ $o->order_number ?? '-' }}</td>
                    <td class="px-6 py-4 theme-text-secondary">
                        @if($o->vendor)
                            <a href="{{ route('admin.vendors.show', $o->vendor->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline">{{ $o->vendor->name }}</a>
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($o->status)
                            <x-status-badge :status="$o->status" />
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2 items-center flex-wrap">
                            <x-action-button 
                                href="{{ route('admin.orders.show', $o->id) }}"
                                type="secondary"
                                size="sm"
                            >
                                View
                            </x-action-button>
                            <x-action-button 
                                href="{{ route('admin.orders.edit', $o->id) }}"
                                type="secondary"
                                size="sm"
                            >
                                Edit
                            </x-action-button>
                            
                            <form action="{{ route('admin.orders.update-status', $o->id) }}" method="POST" class="inline flex gap-2">
                                @csrf
                                <select name="status" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                    <option value="pending" {{ ($o->status ?? '') === 'pending' ? 'selected' : '' }}>pending</option>
                                    <option value="processing" {{ ($o->status ?? '') === 'processing' ? 'selected' : '' }}>processing</option>
                                    <option value="supplied" {{ ($o->status ?? '') === 'supplied' ? 'selected' : '' }}>supplied</option>
                                    <option value="completed" {{ ($o->status ?? '') === 'completed' ? 'selected' : '' }}>completed</option>
                                    <option value="cancelled" {{ ($o->status ?? '') === 'cancelled' ? 'selected' : '' }}>cancelled</option>
                                </select>
                                <x-action-button type="secondary" size="sm">Update</x-action-button>
                            </form>

                            <form action="{{ route('admin.orders.destroy', $o->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 text-sm bg-red-100 text-red-600 rounded hover:bg-red-200 transition" onclick="return confirm('Delete this order?')">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <x-empty-state 
                    title="No orders found"
                    message="There are no orders in the system yet."
                    colspan="5"
                />
            @endforelse
        </tbody>
    </x-data-table>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</x-theme-container>
@endsection
