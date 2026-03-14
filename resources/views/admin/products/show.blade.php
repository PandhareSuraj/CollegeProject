@extends('layouts.app')

@section('title', 'View Product')

@section('content')
<x-theme-container class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold theme-text-primary">{{ $product->name }}</h1>
        <div class="flex gap-3">
            <x-action-button 
                href="{{ route('admin.products.edit', $product->id) }}"
                type="warning"
            >
                Edit
            </x-action-button>
            <x-action-button 
                href="{{ route('admin.products.index') }}"
                type="secondary"
            >
                Back
            </x-action-button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="theme-card p-6">
            <h2 class="text-xl font-semibold theme-text-primary mb-4">Product Details</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="text-sm font-semibold theme-text-secondary">Product Name</label>
                    <p class="theme-text-primary font-medium">{{ $product->name }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold theme-text-secondary">Description</label>
                    <p class="theme-text-primary">{{ $product->description ?? 'N/A' }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold theme-text-secondary">Price</label>
                    <p class="text-lg font-bold text-green-600">₹{{ number_format($product->price, 2) }}</p>
                </div>

                <div>
                    <label class="text-sm font-semibold theme-text-secondary">Stock Quantity</label>
                    <p>
                        <x-stock-badge :quantity="$product->stock_quantity" />
                    </p>
                </div>

                <div>
                    <label class="text-sm font-semibold theme-text-secondary">Created At</label>
                    <p class="theme-text-primary">{{ $product->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-theme-container>
@endsection
