@extends('layouts.app')

@section('title', 'View Product')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1><i class="fas fa-box"></i> {{ $product->name }}</h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Product Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label><strong>Product Name:</strong></label>
                    <p>{{ $product->name }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Description:</strong></label>
                    <p>{{ $product->description ?? 'N/A' }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Price:</strong></label>
                    <p style="font-size: 1.3rem; color: #27ae60;">₹{{ number_format($product->price, 2) }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Stock Quantity:</strong></label>
                    <p>
                        <span class="badge" style="font-size: 1rem; padding: 0.5rem 1rem; background-color: {{ $product->stock_quantity > 0 ? '#27ae60' : '#e74c3c' }};">
                            {{ $product->stock_quantity }} units
                        </span>
                    </p>
                </div>

                <div class="mb-3">
                    <label><strong>Created At:</strong></label>
                    <p>{{ $product->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
