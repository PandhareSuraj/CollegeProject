@extends('layouts.app')

@section('title', 'Stationary Requests')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-4xl font-bold text-gray-900 flex items-center gap-3">
            <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
            </svg>
            Stationary Requests
        </h1>
        @if(Auth::user()->isTeacher())
            <a href="{{ route('requests.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
                Create Request
            </a>
        @endif
    </div>

    <!-- Requests Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Request ID</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Department</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Requested By</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-900 font-semibold">#{{ $request->id }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $request->department->name }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ optional($request->requester)->name ?? 'Unknown' }}</td>
                            <td class="px-6 py-3 text-gray-900">₹{{ number_format($request->total_amount, 2) }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                @if($request->isPending())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Pending</span>
                                @elseif($request->isHodApproved())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">HOD Approved</span>
                                @elseif($request->isPrincipalApproved())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">Principal Approved</span>
                                @elseif($request->isTrustApproved())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800">Trust Approved</span>
                                @elseif($request->isSentToProvider())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Sent to Provider</span>
                                @elseif($request->isCompleted())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                                @elseif($request->isRejected())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-3">
                                <a href="{{ route('requests.show', $request->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
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
                            <td colspan="7" class="px-6 py-8 text-center text-gray-500">No requests found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-8">
        {{ $requests->links() }}
    </div>
</div>
@endsection
