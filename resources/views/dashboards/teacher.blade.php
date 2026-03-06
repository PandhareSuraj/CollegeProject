@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10.5 1.5H5.75a2.25 2.25 0 00-2.25 2.25v12a2.25 2.25 0 002.25 2.25h8.5a2.25 2.25 0 002.25-2.25V6m-11-4h4v4m0-4l4 4"/>
        </svg>
        Teacher Dashboard
    </h1>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $totalRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">My Requests</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-amber-500">{{ $pendingRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Pending</div>
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

    <!-- Recent Requests Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1 4.5 4.5 0 11-4.814 6.98z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">My Recent Requests</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Request ID</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-3 text-gray-900 font-semibold">#{{ $request->id }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3 text-gray-900 font-semibold">₹{{ number_format($request->total_amount, 2) }}</td>
                            <td class="px-6 py-3">
                                @if($request->isPending())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900 dark:bg-opacity-30 text-orange-800 dark:text-orange-300">Pending</span>
                                @elseif($request->isHodApproved())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300">HOD Approved</span>
                                @elseif($request->isPrincipalApproved())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 text-purple-800 dark:text-purple-300">Principal Approved</span>
                                @elseif($request->isTrustApproved())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-teal-100 dark:bg-teal-900 dark:bg-opacity-30 text-teal-800 dark:text-teal-300">Trust Approved</span>
                                @elseif($request->isSentToProvider())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 dark:bg-yellow-900 dark:bg-opacity-30 text-yellow-800 dark:text-yellow-300">Sent to Provider</span>
                                @elseif($request->isCompleted())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-800 dark:text-green-300">Completed</span>
                                @elseif($request->isRejected())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-red-100 dark:bg-red-900 dark:bg-opacity-30 text-red-800 dark:text-red-300">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <a href="{{ route('requests.show', $request->id) }}" class="inline-flex items-center gap-1 px-3 py-1 text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Request Button & Filter Buttons -->
    <div class="mb-8 flex flex-col gap-4">
        <a href="{{ route('requests.create') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 dark:hover:bg-blue-800 transition w-full md:w-auto">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/>
            </svg>
            Create New Request
        </a>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('requests.index', ['status' => '']) }}" class="px-4 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition text-center">My Requests</a>
            <a href="{{ route('requests.index', ['status' => 'pending']) }}" class="px-4 py-3 border border-amber-600 text-amber-600 font-semibold rounded-lg hover:bg-amber-50 transition text-center">Pending</a>
            <a href="{{ route('requests.index', ['status' => 'hod_approved']) }}" class="px-4 py-3 border border-green-600 text-green-600 font-semibold rounded-lg hover:bg-green-50 transition text-center">Approved</a>
            <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="px-4 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition text-center">Completed</a>
        </div>
    </div>

    <!-- Approved Products -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Approved Products</h2>
        </div>
        <div class="p-6">
            @if(isset($approvedProducts) && count($approvedProducts))
                <div class="space-y-2">
                    @foreach($approvedProducts as $product)
                        <div class="flex justify-between items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50">
                            <span class="text-gray-900 font-medium">{{ $product->name }}</span>
                            <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300 text-sm font-semibold rounded-full">₹{{ number_format($product->price, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">No approved products to display.</div>
            @endif
        </div>
    </div>
</div>
@endsection
