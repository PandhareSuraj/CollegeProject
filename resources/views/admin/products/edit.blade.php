@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<x-theme-container class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Edit Product" />

    <div class="theme-card p-6 space-y-6">
        <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-form-input 
                name="name"
                label="Product Name"
                placeholder="Enter product name"
                :value="$product->name"
                required
            />

            <x-form-textarea 
                name="description"
                label="Description"
                rows="4"
                placeholder="Enter product description"
                :value="$product->description"
            />

            <x-form-input 
                name="price"
                label="Price (₹)"
                type="number"
                step="0.01"
                placeholder="0.00"
                :value="$product->price"
                required
            />

            <x-form-input 
                name="stock_quantity"
                label="Stock Quantity"
                type="number"
                placeholder="0"
                :value="$product->stock_quantity"
                required
            />

            <div class="flex gap-3 pt-4">
                <x-form-button type="submit" variant="primary">
                    Update Product
                </x-form-button>
                <x-action-button 
                    href="{{ route('admin.products.index') }}"
                    type="secondary"
                >
                    Cancel
                </x-action-button>
            </div>
        </form>
    </div>
</x-theme-container>
@endsection
