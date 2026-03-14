@props([
    'request',
])

<span class="inline-block px-3 py-1 rounded-full text-xs font-medium
    @if($request->isPending())
        bg-orange-100 text-orange-800
    @elseif($request->isHodApproved())
        bg-blue-100 text-blue-800
    @elseif($request->isPrincipalApproved())
        bg-purple-100 text-purple-800
    @elseif($request->isTrustApproved())
        bg-teal-100 text-teal-800
    @elseif($request->isSentToProvider())
        bg-yellow-100 text-yellow-800
    @elseif($request->isCompleted())
        bg-green-100 text-green-800
    @elseif($request->isRejected())
        bg-red-100 text-red-800
    @else
        bg-gray-100 text-gray-800
    @endif
">
    @if($request->isPending())
        Pending
    @elseif($request->isHodApproved())
        HOD Approved
    @elseif($request->isPrincipalApproved())
        Principal Approved
    @elseif($request->isTrustApproved())
        Trust Approved
    @elseif($request->isSentToProvider())
        Sent to Provider
    @elseif($request->isCompleted())
        Completed
    @elseif($request->isRejected())
        Rejected
    @else
        Unknown
    @endif
</span>
