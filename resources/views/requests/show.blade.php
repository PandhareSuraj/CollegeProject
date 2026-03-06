@extends('layouts.app')

@section('title', 'View Request #' . $stationaryRequest->id)

@section('content')
<div class="mb-4">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-3xl font-bold"><i class="fas fa-file-invoice"></i> Request #{{ $stationaryRequest->id }}</h1>
        <div class="flex gap-2">
            @if($stationaryRequest->isPending() && (int) $stationaryRequest->requested_by === (int) Auth::id())
                <a href="{{ route('requests.edit', $stationaryRequest->id) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition">
                    <i class="fas fa-edit"></i> Edit
                </a>
                <form action="{{ route('requests.destroy', $stationaryRequest->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition" onclick="return confirm('Are you sure?')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 space-y-6">
        <!-- Request Details -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <h5 class="font-semibold text-lg"><i class="fas fa-info-circle"></i> Request Details</h5>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <strong class="text-gray-700">Request ID:</strong>
                        <p class="text-gray-900 font-medium">#{{ $stationaryRequest->id }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Status:</strong>
                        <div class="mt-1">
                            @if($stationaryRequest->isPending())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">Pending</span>
                            @elseif($stationaryRequest->isHodApproved())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">HOD Approved</span>
                            @elseif($stationaryRequest->isPrincipalApproved())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">Principal Approved</span>
                            @elseif($stationaryRequest->isTrustApproved())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">Trust Approved</span>
                            @elseif($stationaryRequest->isSentToProvider())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">Sent to Provider</span>
                            @elseif($stationaryRequest->isCompleted())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">Completed</span>
                            @elseif($stationaryRequest->isRejected())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">Rejected</span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <strong class="text-gray-700">Department:</strong>
                        <p class="text-gray-900 font-medium">{{ $stationaryRequest->department->name }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Requested By:</strong>
                        <p class="text-gray-900 font-medium">{{ optional($stationaryRequest->requestedBy())->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Date:</strong>
                        <p class="text-gray-900 font-medium">{{ $stationaryRequest->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Total Amount:</strong>
                        <p class="text-xl font-bold text-green-600">₹{{ number_format($stationaryRequest->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Items -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <h5 class="font-semibold text-lg"><i class="fas fa-shopping-cart"></i> Items</h5>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Product</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Quantity</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Price</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($stationaryRequest->items as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 text-gray-900">{{ $item->product->name }}</td>
                                <td class="px-6 py-4 text-gray-900">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-gray-900">₹{{ number_format($item->price, 2) }}</td>
                                <td class="px-6 py-4 font-medium text-gray-900">₹{{ number_format($item->subtotal, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Approval Timeline -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <h5 class="font-semibold text-lg"><i class="fas fa-history"></i> Approval Timeline</h5>
            </div>
            <div class="p-6">
                @if($stationaryRequest->approvals->count() > 0)
                    <div class="space-y-4">
                        @foreach($stationaryRequest->approvals as $approval)
                            <div class="pb-4 border-b border-gray-200 last:border-b-0">
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-4 sm:items-center mb-2">
                                    <div>
                                        <strong class="text-gray-700">{{ ucfirst($approval->role) }}</strong>
                                    </div>
                                    <div>
                                        <strong class="text-gray-700">{{ optional($approval->approver)->name ?? 'Unknown' }}</strong>
                                    </div>
                                    <div>
                                        @if($approval->isApproved())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Approved</span>
                                        @elseif($approval->isRejected())
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Rejected</span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">Pending</span>
                                        @endif
                                    </div>
                                    <div class="text-right text-sm text-gray-600 dark:text-gray-300">
                                        {{ $approval->created_at->format('M d, Y') }}
                                    </div>
                                </div>
                                @if($approval->remarks)
                                    <div class="text-sm text-gray-600 dark:text-gray-300">
                                        <strong>Remarks:</strong> {{ $approval->remarks }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">No approvals yet.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Approval Action Panel -->
    <div>
        @if(in_array(Auth::user()->role, ['hod', 'principal', 'trust_head', 'admin']) && 
            (($stationaryRequest->isPending() && Auth::user()->isHOD() && $stationaryRequest->department_id === Auth::user()->department_id) ||
             ($stationaryRequest->isHodApproved() && Auth::user()->isPrincipal()) ||
             ($stationaryRequest->isPrincipalApproved() && Auth::user()->isTrustHead()) ||
             ($stationaryRequest->isTrustApproved() && Auth::user()->isAdmin())))
            <div class="sticky top-24 bg-white rounded-lg border border-gray-200 shadow-sm">
                <div class="px-6 py-4 border-b border-gray-200 bg-yellow-500">
                    <h5 class="font-semibold text-lg text-white"><i class="fas fa-check-circle"></i> Action Required</h5>
                </div>
                <div class="p-6">
                    <p class="text-gray-600 mb-4">This request is waiting for your approval.</p>
                    <a href="{{ route('approvals.show', $stationaryRequest->id) }}" class="block w-full text-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg font-medium transition">
                        <i class="fas fa-check"></i> Review & Approve
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
