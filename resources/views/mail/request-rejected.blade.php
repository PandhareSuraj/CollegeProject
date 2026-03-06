@component('mail::message')
# Request Rejected

Hello {{ $requestorName }},

Unfortunately, your request **#{{ $request->id }}** has been **rejected** by the **{{ $approverRole }}** ({{ $approverName }}).

@component('mail::panel')
**Request Details:**
- Department: {{ $departmentName }}
- Status: Rejected
- Items: {{ $itemCount }}
- Total Amount: ₹{{ number_format($totalAmount, 2) }}
- Rejected By: {{ $approverName }}
@endcomponent

## Reason for Rejection

@component('mail::panel')
{{ $reason }}
@endcomponent

## Next Steps

Your request has been rejected and cannot proceed further in the approval process. You can:

1. Create a new request with modifications based on the feedback
2. Contact the {{ $approverRole }} for clarification on the rejection reason
3. Review the request details and try again with a revised submission

You can view the rejected request details by logging into your account:

@component('mail::button', ['url' => url('/requests/' . $request->id)])
View Request
@endcomponent

If you have any questions or need further clarification, please contact your department's management.

Thank you,
Campus Store Management System
@endcomponent
