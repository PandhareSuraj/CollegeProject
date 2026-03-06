@component('mail::message')
# Request Completed and Supplied

Hello {{ $requestorName }},

Great news! Your request **#{{ $request->id }}** has been successfully completed and all items have been supplied.

@component('mail::panel')
**Request Details:**
- Department: {{ $departmentName }}
- Status: Completed
- Items: {{ $itemCount }}
- Total Amount: ₹{{ number_format($totalAmount, 2) }}
- Completion Date: {{ $completedDate }}
- Supplied By: {{ $supplier->name }}
@endcomponent

## What Happens Next

All items from your request have been delivered and are ready for use. If you have any issues with the received items or have concerns about the delivery, please contact your department management or the supplier.

You can view the request details and complete approval history by logging into your account:

@component('mail::button', ['url' => url('/requests/' . $request->id)])
View Request Details
@endcomponent

Thank you for using Campus Store Management System!

Best regards,
Campus Store Management System
@endcomponent
