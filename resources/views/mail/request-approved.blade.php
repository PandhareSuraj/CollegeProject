@component('mail::message')
# Request Approved

Hello {{ $requestorName }},

Your request **#{{ $request->id }}** has been approved by the **{{ $approverRole }}** ({{ $approverName }}).

@component('mail::panel')
**Request Details:**
- Department: {{ $departmentName }}
- Status: {{ ucfirst(str_replace('_', ' ', $request->status)) }}
- Items: {{ $itemCount }}
- Total Amount: ₹{{ number_format($totalAmount, 2) }}
- Approved By: {{ $approverName }}
@endcomponent

## Next Steps

@if($request->status === 'pending')
Your request has been approved by the HOD and is now pending Principal's approval.
@elseif($request->status === 'hod_approved')
Your request has been approved by the Principal and is now pending Trust Head's approval.
@elseif($request->status === 'principal_approved')
Your request has been approved by the Trust Head and is now pending final Admin authorization.
@elseif($request->status === 'trust_approved')
Your request has been approved and sent to the provider for supply.
@elseif($request->status === 'sent_to_provider')
Your request is now with the provider for supply.
@endif

You can view the complete request details and approval timeline by logging into your account:

@component('mail::button', ['url' => url('/requests/' . $request->id)])
View Request
@endcomponent

Thank you,
Campus Store Management System
@endcomponent
