@extends('layouts.app')

@section('title','Order Details')

@section('content')
<x-theme-container class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Order #{{ $order->order_number }}" />

    <div class="theme-card p-6">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <div>
                <p class="text-xs font-semibold theme-text-secondary">Order Number</p>
                <p class="text-lg font-semibold theme-text-primary">#{{ $order->order_number }}</p>
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
                <p class="text-xs font-semibold theme-text-secondary">Created</p>
                <p class="font-semibold theme-text-primary">{{ $order->created_at->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Order Items Table -->
        <h2 class="text-xl font-semibold theme-text-primary mb-4 mt-6">Order Items</h2>
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

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3">
        <x-action-button 
            href="{{ route('admin.orders.edit', $order->id) }}"
            type="primary"
        >
            Edit Order
        </x-action-button>
        <x-action-button 
            href="{{ route('admin.orders.index') }}"
            type="secondary"
        >
            Back to Orders
        </x-action-button>
    </div>
</x-theme-container>
@endsection
