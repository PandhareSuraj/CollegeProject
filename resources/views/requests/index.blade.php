@extends('layouts.app')

@section('title', 'Stationary Requests')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
    </svg>
    <h2 class="navbar-title">Requests</h2>
</div>
@endsection

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Stationary Requests">
        @if(Auth::user()->isTeacher())
            <x-action-button 
                href="{{ route('requests.create') }}"
                type="primary"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Create Request
            </x-action-button>
        @endif
    </x-page-header>

    <x-data-table>
        <x-table-header :columns="['Request ID', 'Department', 'Requested By', 'Amount', 'Date', 'Status', 'Actions']" />
        <tbody>
            @forelse($requests as $request)
                <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                    <td class="px-6 py-3 text-gray-900 dark:text-gray-100 font-semibold">#{{ $request->id }}</td>
                    <td class="px-6 py-3 theme-text-secondary">{{ $request->department->name }}</td>
                    <td class="px-6 py-3 theme-text-secondary">{{ optional($request->requester)->name ?? 'Unknown' }}</td>
                    <td class="px-6 py-3 text-gray-900 dark:text-gray-100">₹{{ number_format($request->total_amount, 2) }}</td>
                    <td class="px-6 py-3 theme-text-secondary">{{ $request->created_at->format('M d, Y') }}</td>
                    <td class="px-6 py-3">
                        <x-request-status-badge :request="$request" />
                    </td>
                    <td class="px-6 py-3">
                        <x-action-button 
                            href="{{ route('requests.show', $request->id) }}"
                            type="primary"
                            size="sm"
                        >
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                            View
                        </x-action-button>
                    </td>
                </tr>
            @empty
                <x-empty-state 
                    title="No requests found"
                    message="There are no requests in the system yet."
                    action="{{ Auth::user()->isTeacher() ? route('requests.create') : null }}"
                    actionLabel="Create Request"
                    colspan="7"
                />
            @endforelse
        </tbody>
    </x-data-table>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $requests->links() }}
    </div>
</div>
@endsection
