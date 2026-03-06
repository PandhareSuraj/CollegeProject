@extends('layouts.app')

@section('title','Orders')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Orders</h1>
    </div>

    <!-- Create Bulk Orders Button -->
    <div class="mb-6">
        <form method="POST" action="{{ route('admin.orders.create-bulk') }}" class="inline">
            @csrf
            <button class="px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition font-medium">
                Create All Missing Orders
            </button>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700 w-12">#</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Order No</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Vendor</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Status</th>
                    <th class="px-6 py-3 text-left font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $o)
                    <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4 text-gray-900">{{ $o->id }}</td>
                        <td class="px-6 py-4 text-gray-900 font-medium">{{ $o->order_number ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-600 dark:text-gray-300">
                            @if($o->vendor)
                                <a href="{{ route('admin.vendors.show', $o->vendor->id) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline">{{ $o->vendor->name }}</a>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($o->status)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($o->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($o->status === 'processing') bg-blue-100 text-blue-800
                                    @elseif($o->status === 'supplied') bg-cyan-100 text-cyan-800
                                    @elseif($o->status === 'completed') bg-green-100 text-green-800
                                    @elseif($o->status === 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif
                                ">
                                    {{ ucfirst($o->status) }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2 items-center flex-wrap">
                                <a href="{{ route('admin.orders.show', $o->id) }}" class="px-2 py-1 text-sm border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">View</a>
                                <a href="{{ route('admin.orders.edit', $o->id) }}" class="px-2 py-1 text-sm border border-gray-300 text-gray-700 rounded hover:bg-gray-50 transition">Edit</a>
                                
                                <form action="{{ route('admin.orders.update-status', $o->id) }}" method="POST" class="inline flex gap-2">
                                    @csrf
                                    <select name="status" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-600">
                                        <option value="pending" {{ ($o->status ?? '') === 'pending' ? 'selected' : '' }}>pending</option>
                                        <option value="processing" {{ ($o->status ?? '') === 'processing' ? 'selected' : '' }}>processing</option>
                                        <option value="supplied" {{ ($o->status ?? '') === 'supplied' ? 'selected' : '' }}>supplied</option>
                                        <option value="completed" {{ ($o->status ?? '') === 'completed' ? 'selected' : '' }}>completed</option>
                                        <option value="cancelled" {{ ($o->status ?? '') === 'cancelled' ? 'selected' : '' }}>cancelled</option>
                                    </select>
                                    <button type="submit" class="px-2 py-1 text-sm border border-blue-300 text-blue-600 rounded hover:bg-blue-50 transition">Update</button>
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
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $orders->links() }}
    </div>
</div>
@endsection
