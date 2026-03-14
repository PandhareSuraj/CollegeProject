@extends('layouts.app')

@section('title','Edit Order')

@section('content')
<x-theme-container class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Edit Order #{{ $order->order_number }}" />

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Form -->
        <div class="lg:col-span-2 theme-card p-6">
            <h2 class="text-xl font-semibold theme-text-primary mb-4">Order Details</h2>
            <form method="POST" action="{{ route('admin.orders.update', $order->id) }}" class="space-y-4">
                @csrf
                @method('PUT')

                <x-form-select 
                    name="vendor_id"
                    label="Vendor"
                >
                    <option value="">-- Select Vendor --</option>
                    @foreach($vendors as $v)
                        <option value="{{ $v->id }}" {{ $order->vendor_id == $v->id ? 'selected' : '' }}>
                            {{ $v->name }}
                        </option>
                    @endforeach
                </x-form-select>

                <x-form-select 
                    name="status"
                    label="Status"
                >
                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="ordered" {{ $order->status == 'ordered' ? 'selected' : '' }}>Ordered</option>
                    <option value="received" {{ $order->status == 'received' ? 'selected' : '' }}>Received</option>
                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </x-form-select>

                <div class="pt-4">
                    <x-form-button type="submit" variant="primary">
                        Save Changes
                    </x-form-button>
                </div>
            </form>
        </div>

        <!-- Order Summary -->
        <div class="theme-card p-6">
            <h2 class="text-xl font-semibold theme-text-primary mb-4">Order Summary</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Order Number</p>
                    <p class="font-semibold theme-text-primary">#{{ $order->order_number }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Vendor</p>
                    <p class="font-semibold theme-text-primary">{{ optional($order->vendor)->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Status</p>
                    <x-status-badge :status="$order->status" />
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Total Items</p>
                    <p class="font-semibold theme-text-primary">{{ $order->items_count }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Created</p>
                    <p class="font-semibold theme-text-primary">{{ $order->created_at->format('M d, Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Items Table -->
    <div class="mt-8">
        <h2 class="text-xl font-semibold theme-text-primary mb-4">Order Items</h2>
        <x-data-table>
            <x-table-header :columns="['Product', 'Quantity', 'Unit Price', 'Subtotal']" />
            <tbody>
                @forelse($order->items as $item)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-3 theme-text-primary">
                            {{ optional($item->product)->name ?? 'Product #' . $item->product_id }}
                        </td>
                        <td class="px-6 py-3 theme-text-secondary">{{ $item->quantity }}</td>
                        <td class="px-6 py-3 theme-text-secondary">₹{{ number_format($item->price, 2) }}</td>
                        <td class="px-6 py-3 font-semibold theme-text-primary">₹{{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                @empty
                    <x-empty-state title="No items in this order" colspan="4" />
                @endforelse
            </tbody>
        </x-data-table>
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <x-action-button 
            href="{{ route('admin.orders.index') }}"
            type="secondary"
        >
            Back to Orders
        </x-action-button>
    </div>
</x-theme-container>
@endsection
