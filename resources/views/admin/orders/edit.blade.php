@extends('layouts.app')

@section('title','Edit Order')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300 mb-6 flex items-center gap-2">
        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
            <path d="M16 16a2 2 0 11-4 0 2 2 0 014 0z"/>
            <path d="M4 12a2 2 0 11-4 0 2 2 0 014 0z"/>
        </svg>
        Edit Order #{{ $order->order_number }}
    </h1>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Form -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-300 mb-4">Order Details</h2>
            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <!-- Vendor Selection -->
                <div>
                    <label for="vendor_id" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Vendor</label>
                    <select id="vendor_id" name="vendor_id"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('vendor_id') border-red-500 @enderror">
                        <option value="">-- Select Vendor --</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}" {{ $order->vendor_id == $v->id ? 'selected' : '' }}>
                                {{ $v->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('vendor_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Status</label>
                    <select id="status" name="status"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('status') border-red-500 @enderror">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                        <option value="received" {{ $order->status == 'received' ? 'selected' : '' }}>Received</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Save Button -->
                <div class="pt-4">
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="bg-gray-50 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-300 mb-4">Order Summary</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-600 dark:text-gray-300">Order Number:</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-300">#{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-300">Vendor:</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-300">{{ optional($order->vendor)->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-300">Status:</p>
                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-300">Total Items:</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-300">{{ $order->items_count }}</p>
                </div>
                <div>
                    <p class="text-gray-600 dark:text-gray-300">Created:</p>
                    <p class="font-semibold text-gray-900 dark:text-gray-300">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Table -->
    <div class="mt-8 bg-white rounded-lg shadow overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-300">Order Items</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-300">Product</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-300">Quantity</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900 dark:text-gray-300">Unit Price</th>
                        <th class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-gray-300">Subtotal</th>
                    </tr>
                </thead>
                <tbody divide-y divide-gray-200">
                    @forelse($order->items as $item)
                        <tr class="border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-900 dark:text-gray-300">
                                {{ optional($item->product)->name ?? 'Product #' . $item->product_id }}
                            </td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $item->quantity }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">₹{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-3 text-right font-semibold text-gray-900 dark:text-gray-300">₹{{ number_format($item->subtotal, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No items in this order</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center gap-2 px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
            </svg>
            Back to Orders
        </a>
    </div>
</div>
@endsection
