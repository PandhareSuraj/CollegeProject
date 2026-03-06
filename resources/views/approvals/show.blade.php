@extends('layouts.app')

@section('title', 'Approve Request #' . $stationaryRequest->id)

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold"><i class="fas fa-check-circle"></i> Review Request #{{ $stationaryRequest->id }}</h1>
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
                        <strong class="text-gray-700">Department:</strong>
                        <p class="text-gray-900 font-medium">{{ $stationaryRequest->department->name }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Requested By:</strong>
                        <p class="text-gray-900 font-medium">{{ optional($stationaryRequest->requester)->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Date:</strong>
                        <p class="text-gray-900 font-medium">{{ $stationaryRequest->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700">Current Status:</strong>
                        <div class="mt-1">
                            @if($stationaryRequest->isPending())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800">Pending</span>
                            @elseif($stationaryRequest->isHodApproved())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">HOD Approved</span>
                            @elseif($stationaryRequest->isPrincipalApproved())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">Principal Approved</span>
                            @elseif($stationaryRequest->isTrustApproved())
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-teal-100 text-teal-800">Trust Approved</span>
                            @endif
                        </div>
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

        <!-- Previous Approvals -->
        <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <h5 class="font-semibold text-lg"><i class="fas fa-history"></i> Previous Approvals</h5>
            </div>
            <div class="p-6">
                @if($stationaryRequest->approvals_count > 0)
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
                    <p class="text-gray-500">No previous approvals.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Approval Form -->
    <div>
        <div class="sticky top-24 bg-white rounded-lg border border-gray-200 shadow-sm">
            <div class="px-6 py-4 border-b border-gray-200 bg-yellow-500">
                <h5 class="font-semibold text-lg text-white"><i class="fas fa-gavel"></i> Your Decision</h5>
            </div>
            <div class="p-6">
                <p class="text-gray-600 mb-4">
                    <strong>Approver Role:</strong> {{ ucfirst($user->role) }}
                </p>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2"><strong>Remarks (Optional)</strong></label>
                    <textarea id="remarks" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Add any remarks..."></textarea>
                </div>

                <div class="space-y-3">
                    <form action="{{ route('approvals.store', $stationaryRequest->id) }}" method="POST" id="approveForm" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="approved">
                        <input type="hidden" name="remarks" id="approveRemarks">
                        <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition" {{ !$canApprove ? 'disabled' : '' }}>
                            <i class="fas fa-check-circle"></i> Approve Request
                        </button>
                    </form>

                    <form action="{{ route('approvals.store', $stationaryRequest->id) }}" method="POST" id="rejectForm" style="display:inline;">
                        @csrf
                        <input type="hidden" name="status" value="rejected">
                        <input type="hidden" name="remarks" id="rejectRemarks">
                        <button type="button" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition" onclick="confirmReject()" {{ !$canApprove ? 'disabled' : '' }}>
                            <i class="fas fa-times-circle"></i> Reject Request
                        </button>
                    </form>

                    <a href="{{ route('dashboard') }}" class="block w-full text-center px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>

                <!-- Workflow Status -->
                @if(!$canApprove)
                    <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-900">
                            You can view the request details but you are not eligible to approve it at this stage.
                            If you believe this is incorrect, please contact the administrator.
                        </p>
                        @if(isset($diagnostics))
                            <hr class="my-3 border-blue-200"/>
                            <div class="text-xs text-blue-900">
                                <strong>Diagnostics:</strong>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li>Is request owner: {{ $diagnostics['is_request_owner'] ? 'YES' : 'NO' }}</li>
                                    <li>Request status allowed: {{ $diagnostics['is_status_allowed'] ? 'YES' : 'NO' }}</li>
                                    <li>HOD status OK (pending & same dept): {{ $diagnostics['hod_status_ok'] ? 'YES' : 'NO' }}</li>
                                    <li>Already HOD approved: {{ $diagnostics['already_hod_approved'] ? 'YES' : 'NO' }}</li>
                                </ul>

                                <strong class="block mt-3">Raw values:</strong>
                                <ul class="list-disc list-inside mt-2 space-y-1">
                                    <li>User ID: {{ $diagnostics['user_id'] }}</li>
                                    <li>User role: {{ $diagnostics['user_role'] }}</li>
                                    <li>User department ID: {{ $diagnostics['user_department_id'] }}</li>
                                    <li>Request department ID: {{ $diagnostics['request_department_id'] }}</li>
                                    <li>Request status: {{ $diagnostics['request_status'] }}</li>
                                    <li>Requested by: {{ $diagnostics['requested_by'] }}</li>
                                </ul>
                            </div>
                        @endif
                    </div>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <h6 class="mb-4 font-semibold text-gray-700"><i class="fas fa-stream"></i> Workflow Status</h6>
                    <div class="space-y-3 text-sm">
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $stationaryRequest->isPending() || $stationaryRequest->isHodApproved() ? '#3498db' : '#95a5a6' }}; color: white;">
                                <i class="fas fa-user-tie me-1"></i> HOD
                            </span>
                            @if($stationaryRequest->isPending() || $stationaryRequest->isHodApproved())
                                <small class="text-gray-600 block mt-1">Awaiting HOD Approval</small>
                            @else
                                <small class="text-green-600 block mt-1">✓ HOD Approved</small>
                            @endif
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $stationaryRequest->isHodApproved() ? '#3498db' : '#95a5a6' }}; color: white;">
                                <i class="fas fa-graduation-cap"></i> Principal
                            </span>
                            @if($stationaryRequest->isHodApproved())
                                <small class="text-gray-600 block mt-1">Awaiting Principal Approval</small>
                            @elseif($stationaryRequest->isPrincipalApproved() || $stationaryRequest->isTrustApproved() || $stationaryRequest->isSentToProvider())
                                <small class="text-green-600 block mt-1">✓ Principal Approved</small>
                            @else
                                <small class="text-gray-600 block mt-1">Pending HOD approval</small>
                            @endif
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $stationaryRequest->isPrincipalApproved() ? '#3498db' : '#95a5a6' }}; color: white;">
                                <i class="fas fa-shield-alt"></i> Trust Head
                            </span>
                            @if($stationaryRequest->isPrincipalApproved())
                                <small class="text-gray-600 block mt-1">Awaiting Trust Head Approval</small>
                            @elseif($stationaryRequest->isTrustApproved() || $stationaryRequest->isSentToProvider())
                                <small class="text-green-600 block mt-1">✓ Trust Head Approved</small>
                            @else
                                <small class="text-gray-600 block mt-1">Pending Principal approval</small>
                            @endif
                        </div>
                        <div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" style="background-color: {{ $stationaryRequest->isSentToProvider() ? '#27ae60' : '#95a5a6' }}; color: white;">
                                <i class="fas fa-truck"></i> Provider
                            </span>
                            @if($stationaryRequest->isSentToProvider())
                                <small class="text-green-600 block mt-1">✓ Ready for Supply</small>
                            @else
                                <small class="text-gray-600 block mt-1">Pending Trust Head approval</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    function confirmReject() {
        const remarksEl = document.getElementById('remarks');
        const remarks = remarksEl ? remarksEl.value : '';
        if (confirm('Are you sure you want to reject this request? This action cannot be undone.')) {
            const rejectRemarks = document.getElementById('rejectRemarks');
            const rejectForm = document.getElementById('rejectForm');
            if (rejectRemarks) rejectRemarks.value = remarks;
            if (rejectForm) rejectForm.submit();
        }
    }

    // Expose confirmReject to the global scope for the inline onclick on the reject button
    window.confirmReject = confirmReject;

    // Ensure approve form always includes the remarks value before submitting.
    const approveForm = document.getElementById('approveForm');
    const approveRemarks = document.getElementById('approveRemarks');
    const remarksEl = document.getElementById('remarks');

    if (approveForm) {
        // Defensive submit handler
        approveForm.addEventListener('submit', function (e) {
            if (approveRemarks && remarksEl) {
                approveRemarks.value = remarksEl.value;
            }
            // allow normal submit to continue
        });

        // Also add an explicit click handler on the approve button (in case form.submit is blocked elsewhere)
        const approveButton = approveForm.querySelector('button[type="submit"]');
        if (approveButton) {
            approveButton.addEventListener('click', function (e) {
                if (approveRemarks && remarksEl) {
                    approveRemarks.value = remarksEl.value;
                }
                // If the button is disabled we do nothing
                if (approveButton.disabled) {
                    e.preventDefault();
                    return;
                }
                // otherwise allow the normal submit to proceed
            });
        }
    }
});
</script>

@endsection
