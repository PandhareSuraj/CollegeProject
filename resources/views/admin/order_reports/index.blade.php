@extends('layouts.app')

@section('title','Order Reports')

@section('content')
<div class="container">
    <h1 class="mb-4">Order Reports</h1>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.order-reports.submit') }}" class="row g-2">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">From</label>
                    <input type="date" name="from" class="form-control" value="{{ old('from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">To</label>
                    <input type="date" name="to" class="form-control" value="{{ old('to') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Vendor</label>
                    <select name="vendor" class="form-select">
                        <option value="">All vendors</option>
                        @foreach($vendors as $v)
                            <option value="{{ $v->id }}">{{ $v->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Run Report</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>#</th><th>Order No</th><th>Vendor</th><th>Status</th><th>Created</th></tr></thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td>{{ $o->id }}</td>
                        <td>{{ $o->order_number }}</td>
                        <td>{{ $o->vendor?->name ?? '-' }}</td>
                        <td>{{ $o->status }}</td>
                        <td>{{ $o->created_at->toDateString() }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">No orders found for the selected filter.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
</div>
@endsection
