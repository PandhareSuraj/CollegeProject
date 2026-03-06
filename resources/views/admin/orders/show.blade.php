@extends('layouts.app')

@section('title','Order Details')

@section('content')
<div class="container">
    <h1 class="mb-4">Order Details</h1>

    @if(is_null($order))
        <div class="alert alert-info">No Order model/table exists yet. This is a placeholder view.</div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    @else
        <table class="table table-bordered">
            <tr><th>ID</th><td>{{ $order->id }}</td></tr>
            <tr><th>Order No</th><td>{{ $order->order_number }}</td></tr>
            <tr><th>Vendor</th><td>{{ $order->vendor->name ?? '-' }}</td></tr>
            <tr><th>Status</th><td>{{ $order->status }}</td></tr>
        </table>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
    @endif
</div>
@endsection
