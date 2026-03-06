@extends('layouts.app')

@section('title', 'Principal Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.5 1.5H5.75a2.25 2.25 0 00-2.25 2.25v12a2.25 2.25 0 002.25 2.25h8.5a2.25 2.25 0 002.25-2.25V6m-11-4h4v4m0-4l4 4"/>
        </svg>
        Principal Dashboard
    </h1>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $totalRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Total Requests</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-amber-500">{{ $pendingApprovals }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Pending Approval</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-green-600">{{ $approvedRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Approved</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-blue-600">{{ $completedRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Completed</div>
        </div>
    </div>

    <!-- Pending Approvals Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Requests Awaiting Principal Approval (HOD Approved)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Request ID</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Department</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Requested By</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-3 text-gray-900 font-semibold">#{{ $request->id }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $request->department->name }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ optional($request->requestedBy())->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-3 text-gray-900 font-semibold">₹{{ number_format($request->total_amount, 2) }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                <a href="{{ route('approvals.show', $request->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-amber-600 text-white font-medium rounded hover:bg-amber-700 transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No pending approvals.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('requests.index') }}" class="px-4 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition text-center">View Requests</a>
        <a href="{{ route('requests.index', ['status' => 'hod_approved']) }}" class="px-4 py-3 border border-amber-600 text-amber-600 font-semibold rounded-lg hover:bg-amber-50 transition text-center">Pending Approval</a>
        <a href="{{ route('requests.index', ['status' => 'principal_approved']) }}" class="px-4 py-3 border border-green-600 text-green-600 font-semibold rounded-lg hover:bg-green-50 transition text-center">Approved</a>
        <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="px-4 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition text-center">Completed</a>
    </div>
</div>
@endsection
