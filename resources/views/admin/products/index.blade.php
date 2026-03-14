@extends('layouts.app')

@section('title', 'Products Management')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
    </svg>
    <h2 class="navbar-title">Products</h2>
</div>
@endsection

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Products Management">
        <x-action-button 
            href="{{ route('admin.products.create') }}"
            type="primary"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Product
        </x-action-button>
    </x-page-header>

    <x-data-table>
        <x-table-header :columns="['Name', 'Description', 'Price', 'Stock', 'Actions']" />
        <tbody>
            @forelse($products as $product)
                <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                    <td class="px-6 py-4 theme-text-primary font-medium">{{ $product->name }}</td>
                    <td class="px-6 py-4 theme-text-secondary">{{ Str::limit($product->description, 50) }}</td>
                    <td class="px-6 py-4 theme-text-primary font-medium">₹{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <x-stock-badge :quantity="$product->stock_quantity" />
                    </td>
                    <x-table-row-actions 
                        :view="route('admin.products.show', $product->id)"
                        :edit="route('admin.products.edit', $product->id)"
                        :deleteRoute="route('admin.products.destroy', $product->id)"
                    />
                </tr>
            @empty
                <x-empty-state 
                    title="No products found"
                    message="There are no products in the system yet."
                    action="{{ route('admin.products.create') }}"
                    actionLabel="Create Product"
                    colspan="5"
                />
            @endforelse
        </tbody>
    </x-data-table>

    <div class="mt-6 flex justify-center">
        {{ $products->links() }}
    </div>
</x-theme-container>
@endsection
