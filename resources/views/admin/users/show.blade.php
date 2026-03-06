@extends('layouts.app')

@section('title', 'View User')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1><i class="fas fa-user"></i> {{ $user->name }}</h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">User Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label><strong>Name:</strong></label>
                    <p>{{ $user->name }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Email:</strong></label>
                    <p>{{ $user->email }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Role:</strong></label>
                    <p><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></p>
                </div>

                <div class="mb-3">
                    <label><strong>Department:</strong></label>
                    <p>{{ $user->department->name ?? 'N/A' }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Joined:</strong></label>
                    <p>{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label><strong>Requests Created:</strong></label>
                    <p style="font-size: 1.5rem; color: #3498db;">{{ $requestsCount ?? 0 }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Approvals Given:</strong></label>
                    <p style="font-size: 1.5rem; color: #27ae60;">{{ $approvalsCount ?? 0 }}</p>
                </div>

                @if(($requestsCount ?? 0) === 0 && ($approvalsCount ?? 0) === 0)
                    <p class="text-muted">No statistics available for this user</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if(!empty($hasRecentRequests))
    <div class="card mt-3">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-file-invoice"></i> User's Requests</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Request ID</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentRequests as $request)
                        <tr>
                            <td>#{{ $request->id }}</td>
                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                            <td>₹{{ number_format($request->total_amount, 2) }}</td>
                            <td>
                                @if($request->isPending())
                                    <span class="badge badge-pending">Pending</span>
                                @elseif($request->isApproved())
                                    <span class="badge badge-approved">Approved</span>
                                @elseif($request->isRejected())
                                    <span class="badge badge-rejected">Rejected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
