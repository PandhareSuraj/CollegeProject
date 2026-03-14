@extends('layouts.app')

@section('title', 'View User')

@section('content')
<x-theme-container class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="{{ $user->name }}" icon="user" />

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- User Details -->
        <div class="theme-card p-6">
            <h2 class="text-xl font-semibold theme-text-primary mb-4">User Details</h2>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Name</p>
                    <p class="font-semibold theme-text-primary">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Email</p>
                    <p class="font-semibold theme-text-primary">{{ $user->email }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Role</p>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                        {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Department</p>
                    <p class="font-semibold theme-text-primary">{{ optional($user->department)->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary">Joined</p>
                    <p class="font-semibold theme-text-primary">{{ $user->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="theme-card p-6">
            <h2 class="text-xl font-semibold theme-text-primary mb-4">Statistics</h2>
            <div class="space-y-4">
                <div>
                    <p class="text-xs font-semibold theme-text-secondary mb-1">Requests Created</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $requestsCount ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold theme-text-secondary mb-1">Approvals Given</p>
                    <p class="text-3xl font-bold text-green-600">{{ $approvalsCount ?? 0 }}</p>
                </div>
                @if(($requestsCount ?? 0) === 0 && ($approvalsCount ?? 0) === 0)
                    <p class="text-xs theme-text-secondary">No statistics available for this user</p>
                @endif
            </div>
        </div>
    </div>

    @if(!empty($hasRecentRequests))
    <!-- User Requests -->
    <div class="theme-card">
        <div class="border-b theme-border-primary px-6 py-4">
            <h2 class="text-xl font-semibold theme-text-primary">User's Requests</h2>
        </div>
        <x-data-table>
            <x-table-header :columns="['Request ID', 'Date', 'Amount', 'Status']" />
            <tbody>
                @foreach($recentRequests as $request)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-3 font-semibold theme-text-primary">#{{ $request->id }}</td>
                        <td class="px-6 py-3 theme-text-secondary">{{ $request->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-3 font-semibold theme-text-primary">₹{{ number_format($request->total_amount, 2) }}</td>
                        <td class="px-6 py-3">
                            @if($request->isPending())
                                <x-status-badge :status="'pending'" />
                            @elseif($request->isApproved())
                                <x-status-badge :status="'approved'" />
                            @elseif($request->isRejected())
                                <x-status-badge :status="'rejected'" />
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-data-table>
    </div>
    @endif

    <!-- Action Buttons -->
    <div class="mt-6 flex gap-3">
        <x-action-button 
            href="{{ route('admin.users.edit', $user->id) }}"
            type="primary"
        >
            Edit User
        </x-action-button>
        <x-action-button 
            href="{{ route('admin.users.index') }}"
            type="secondary"
        >
            Back to Users
        </x-action-button>
    </div>
</x-theme-container>
@endsection
