@extends('layouts.app')

@section('title', 'Provider Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-8 flex items-center gap-3">
        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
            <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/>
        </svg>
        Provider Dashboard
    </h1>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-gray-900 dark:text-white">{{ $totalRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Total Requests</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-orange-500">{{ $sentRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Sent to Me</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-4xl font-bold text-green-600">{{ $completedRequests }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300 mt-2">Completed</div>
        </div>
    </div>

    <!-- Requests to Supply Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
            </svg>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Requests for Supply</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Request ID</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Department</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Amount</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Date</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Status</th>
                        <th class="px-6 py-3 text-left font-semibold text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody divide-y divide-gray-200">
                    @forelse($requests as $request)
                        <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-3 text-gray-900 font-semibold">#{{ $request->id }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $request->department->name }}</td>
                            <td class="px-6 py-3 text-gray-900 font-semibold">₹{{ number_format($request->total_amount, 2) }}</td>
                            <td class="px-6 py-3 text-gray-600 dark:text-gray-300">{{ $request->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-3">
                                @if($request->isSentToProvider())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-orange-100 dark:bg-orange-900 dark:bg-opacity-30 text-orange-800 dark:text-orange-300">Sent to Me</span>
                                @elseif($request->isCompleted())
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-800 dark:text-green-300">Completed</span>
                                @endif
                            </td>
                            <td class="px-6 py-3 flex gap-2">
                                <a href="{{ route('requests.show', $request->id) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                    View
                                </a>
                                @if($request->isSentToProvider())
                                    <form action="{{ route('requests.supplied', $request->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 bg-green-600 text-white font-medium rounded hover:bg-green-700 transition" onclick="return confirm('Mark as supplied?')">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Supplied
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">No requests to supply.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Filter Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('requests.index') }}" class="px-4 py-3 border border-blue-600 text-blue-600 font-semibold rounded-lg hover:bg-blue-50 transition text-center">View Requests</a>
        <a href="{{ route('requests.index', ['status' => 'sent_to_provider']) }}" class="px-4 py-3 border border-orange-600 text-orange-600 font-semibold rounded-lg hover:bg-orange-50 transition text-center">Sent to Me</a>
        <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="px-4 py-3 border border-green-600 text-green-600 font-semibold rounded-lg hover:bg-green-50 transition text-center">Completed</a>
    </div>
</div>
@endsection
