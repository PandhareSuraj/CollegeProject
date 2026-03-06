# Notification System Documentation

## Overview

The Campus Store Management System now includes a comprehensive event-driven notification system that automatically sends email notifications to relevant stakeholders when:

1. **Request is Approved** - Notifies requestor and next-level approvers
2. **Request is Rejected** - Notifies requestor with rejection reason
3. **Request is Supplied** - Notifies requestor, HOD, and admin on completion

## Architecture

### Event-Driven Pattern

The notification system uses Laravel's Event/Listener pattern for:
- Loose coupling between controllers and notifications
- Asynchronous processing via queued jobs
- Testable and maintainable code
- Easy to extend with new notification types

### System Flow

```
User Action (Approval/Rejection/Supply)
    ↓
ApprovalController processes decision
    ↓
Event dispatched (if approved/rejected/supplied)
    ↓
Event listener queued execution
    ↓
Mailable sends email
    ↓
Recipient receives email
    ↓
Log entry created (success/failure)
```

## Events

### 1. RequestApproved Event

**File**: `app/Events/RequestApproved.php`

**Triggered When**: Request is approved by HOD, Principal, Trust Head, or Admin

**Data Carried**:
- `StationaryRequest $request` - The request being approved
- `Approval $approval` - The approval record with approver role

**Example Usage**:
```php
use App\Events\RequestApproved;

Event::dispatch(new RequestApproved($request, $approval));
```

---

### 2. RequestRejected Event

**File**: `app/Events/RequestRejected.php`

**Triggered When**: Request is rejected at any approval stage

**Data Carried**:
- `StationaryRequest $request` - The rejected request
- `Approval $approval` - The approval record with rejection details
- `string $reason` - Reason for rejection (from remarks or custom message)

**Example Usage**:
```php
use App\Events\RequestRejected;

Event::dispatch(new RequestRejected($request, $approval, 'Budget exceeded'));
```

---

### 3. RequestSupplied Event

**File**: `app/Events/RequestSupplied.php`

**Triggered When**: Provider marks request as supplied (completion)

**Data Carried**:
- `StationaryRequest $request` - The completed request
- `User $supplier` - The provider who supplied the items

**Example Usage**:
```php
use App\Events\RequestSupplied;

Event::dispatch(new RequestSupplied($request, $provider_user));
```

---

## Listeners

### 1. SendRequestApprovedNotification

**File**: `app/Listeners/SendRequestApprovedNotification.php`

**Triggered By**: `RequestApproved` event

**Recipients**:
- ✅ The request creator (requestor)
- ✅ Next-level approvers (based on current approval stage):
  - HOD approval → Principal notified
  - Principal approval → Trust Head notified
  - Trust Head approval → Admin notified
- ✅ Department HOD (for tracking)

**Email Template**: `resources/views/mail/request-approved.blade.php`

**Implementation**:
```php
// In AppServiceProvider.php
Event::listen(
    RequestApproved::class,
    SendRequestApprovedNotification::class,
);
```

---

### 2. SendRequestRejectedNotification

**File**: `app/Listeners/SendRequestRejectedNotification.php`

**Triggered By**: `RequestRejected` event

**Recipients**:
- ✅ The request creator (requestor)
- ✅ Department HOD (for tracking)
- ✅ Admin (for tracking all rejections)

**Email Template**: `resources/views/mail/request-rejected.blade.php`

**Implementation**:
```php
// In AppServiceProvider.php
Event::listen(
    RequestRejected::class,
    SendRequestRejectedNotification::class,
);
```

---

### 3. SendRequestSuppliedNotification

**File**: `app/Listeners/SendRequestSuppliedNotification.php`

**Triggered By**: `RequestSupplied` event

**Recipients**:
- ✅ The request creator (requestor)
- ✅ Department HOD (for tracking)
- ✅ Admin (for tracking all supplies)

**Email Template**: `resources/views/mail/request-supplied.blade.php`

**Implementation**:
```php
// In AppServiceProvider.php
Event::listen(
    RequestSupplied::class,
    SendRequestSuppliedNotification::class,
);
```

---

## Mailable Classes

### 1. RequestApprovedNotification

**File**: `app/Mail/RequestApprovedNotification.php`

**Queue Support**: Yes (implements `ShouldQueue`)

**Email Data Passed**:
```php
[
    'request' => StationaryRequest,
    'approval' => Approval,
    'approverName' => string,
    'approverRole' => string,
    'requestorName' => string,
    'departmentName' => string,
    'itemCount' => int,
    'totalAmount' => float,
]
```

**Subject Line Format**:
```
Request #{request_id} Approved by {approver_role}
```

---

### 2. RequestRejectedNotification

**File**: `app/Mail/RequestRejectedNotification.php`

**Queue Support**: Yes (implements `ShouldQueue`)

**Email Data Passed**:
```php
[
    'request' => StationaryRequest,
    'approval' => Approval,
    'approverName' => string,
    'approverRole' => string,
    'requestorName' => string,
    'departmentName' => string,
    'reason' => string,
    'itemCount' => int,
    'totalAmount' => float,
]
```

**Subject Line Format**:
```
Request #{request_id} Rejected by {approver_role}
```

---

### 3. RequestSuppliedNotification

**File**: `app/Mail/RequestSuppliedNotification.php`

**Queue Support**: Yes (implements `ShouldQueue`)

**Email Data Passed**:
```php
[
    'request' => StationaryRequest,
    'supplier' => User,
    'requestorName' => string,
    'departmentName' => string,
    'itemCount' => int,
    'totalAmount' => float,
    'completedDate' => string,
]
```

**Subject Line**:
```
Request #{request_id} Successfully Supplied and Completed
```

---

## Email Templates

### 1. Approval Email (request-approved.blade.php)

**Location**: `resources/views/mail/request-approved.blade.php`

**Features**:
- Request details summary
- Approver information
- Next steps information (varies by approval stage)
- Button to view request
- Department and amount details

**Preview**:
```
Subject: Request #123 Approved by HOD

Hello [Requestor Name],

Your request #123 has been approved by the HOD ([Approver Name]).

Request Details:
- Department: Computer Science
- Status: hod_approved
- Items: 2
- Total Amount: ₹5,000
- Approved By: Dr. Smith

Next Steps:
Your request is now pending Principal's approval.

[View Request Button]
```

---

### 2. Rejection Email (request-rejected.blade.php)

**Location**: `resources/views/mail/request-rejected.blade.php`

**Features**:
- Clear rejection notification
- Reason for rejection
- Request details summary
- Guidance for next steps
- Contact information for clarifications

**Preview**:
```
Subject: Request #123 Rejected by HOD

Hello [Requestor Name],

Unfortunately, your request #123 has been rejected by the HOD ([Approver Name]).

Request Details:
- Department: Computer Science
- Status: Rejected
- Items: 2
- Total Amount: ₹5,000

Reason for Rejection:
Budget allocation exceeded for this fiscal year. Please resubmit next quarter.

Next Steps:
1. Create a new request with modifications
2. Contact HOD for clarification
3. Review request details and revise

[View Request Button]
```

---

### 3. Completion Email (request-supplied.blade.php)

**Location**: `resources/views/mail/request-supplied.blade.php`

**Features**:
- Completion notification
- Request summary
- Supplier information
- Completion date
- Guidance on next steps

**Preview**:
```
Subject: Request #123 Successfully Supplied and Completed

Hello [Requestor Name],

Great news! Your request #123 has been successfully completed and all items have been supplied.

Request Details:
- Department: Computer Science
- Status: Completed
- Items: 2
- Total Amount: ₹5,000
- Completion Date: 15 Feb, 2024
- Supplied By: ABC Supplies

[View Request Details Button]
```

---

## Configuration

### Mail Configuration

Ensure your `.env` file has proper mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@campusstore.test
MAIL_FROM_NAME="Campus Store Management"
```

### Queue Configuration

Notifications are queued for asynchronous sending. Configure queue in `.env`:

```env
QUEUE_CONNECTION=database
```

Or for other queue drivers:
```env
QUEUE_CONNECTION=redis
QUEUE_CONNECTION=beanstalkd
```

---

## Integration Points

### 1. ApprovalController Integration

**File**: `app/Http/Controllers/ApprovalController.php`

**Modifications Made**:
- Added `Event` facade import
- Added `RequestApproved`, `RequestRejected`, `RequestSupplied` event imports
- Event dispatch in `store()` method on approval
- Event dispatch in `store()` method on rejection
- Event dispatch in `markSupplied()` method

**Code Example**:
```php
// On approval
Event::dispatch(new RequestApproved($stationaryRequest, $approval));

// On rejection
Event::dispatch(new RequestRejected($stationaryRequest, $approval, $validated['remarks']));

// On supply completion
Event::dispatch(new RequestSupplied($stationaryRequest, $user));
```

---

### 2. EventServiceProvider Registration

**File**: `app/Providers/AppServiceProvider.php`

**Method**: `registerEventListeners()`

**Code**:
```php
protected function registerEventListeners(): void
{
    Event::listen(RequestApproved::class, SendRequestApprovedNotification::class);
    Event::listen(RequestRejected::class, SendRequestRejectedNotification::class);
    Event::listen(RequestSupplied::class, SendRequestSuppliedNotification::class);
}
```

---

## Testing Notifications

### Test in Development

**1. Configure Mailtrap (Recommended for Development)**:
```env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
```

**2. Use Log Driver (For Local Testing)**:
```env
MAIL_DRIVER=log
```

All emails will be logged to `storage/logs/laravel.log`

**3. Manual Testing**:
```php
// In tinker or test
$request = StationaryRequest::first();
$approval = Approval::first();

// Test approval notification
Mail::to('test@example.com')->send(new RequestApprovedNotification($request, $approval));

// Test rejection notification
Mail::to('test@example.com')->send(new RequestRejectedNotification($request, $approval));

// Test supplied notification
Mail::to('test@example.com')->send(new RequestSuppliedNotification($request, $provider));
```

### Monitor Queued Jobs

```bash
# Process queued jobs in development
php artisan queue:work

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

---

## Customization Guide

### Adding a New Notification Type

**Step 1**: Create Event
```php
// app/Events/RequestCompleted.php
class RequestCompleted {
    public function __construct(public StationaryRequest $request) {}
}
```

**Step 2**: Create Mailable
```php
// app/Mail/RequestCompletedNotification.php
class RequestCompletedNotification extends Mailable implements ShouldQueue {
    // ...
}
```

**Step 3**: Create Listener
```php
// app/Listeners/SendRequestCompletedNotification.php
class SendRequestCompletedNotification implements ShouldQueue {
    public function handle(RequestCompleted $event): void {
        Mail::to($event->request->requestedBy->email)
            ->send(new RequestCompletedNotification($event->request));
    }
}
```

**Step 4**: Register in AppServiceProvider
```php
Event::listen(RequestCompleted::class, SendRequestCompletedNotification::class);
```

**Step 5**: Dispatch Event
```php
Event::dispatch(new RequestCompleted($request));
```

---

## Troubleshooting

### Emails Not Being Sent

**Check 1**: Verify mail configuration in `.env`
```bash
php artisan config:show mail
```

**Check 2**: Check Laravel logs
```bash
tail -f storage/logs/laravel.log
```

**Check 3**: Test mail configuration
```bash
php artisan tinker
>>> Mail::to('test@example.com')->send(new \Illuminate\Mail\Message);
```

### Queue Not Processing

**Check 1**: Queue worker running?
```bash
php artisan queue:work
```

**Check 2**: Check queue configuration
```php
// .env
QUEUE_CONNECTION=database
```

**Check 3**: Check failed jobs
```bash
php artisan queue:failed
```

### Incorrect Email Recipients

**Check 1**: Verify user email addresses in database
```php
User::all(['id', 'email', 'role']);
```

**Check 2**: Review listener recipient logic
Example: HOD might not be set, causing notification to skip

---

## Performance Optimization

### Queue Driver Selection

**For Development**: `sync` (immediate, not queued)
```env
QUEUE_CONNECTION=sync
```

**For Production**: `redis` (fastest, requires Redis)
```env
QUEUE_CONNECTION=redis
```

**Fallback**: `database` (uses database table)
```env
QUEUE_CONNECTION=database
```

### Batch Processing

Group related notifications:
```php
// Send to multiple recipients efficiently
Mail::bcc(['email1@example.com', 'email2@example.com'])
    ->send(new RequestApprovedNotification($request, $approval));
```

---

## Audit Trail

All notification attempts are logged:

**Success Log**:
```
[2024-02-15 13:45:23] local.INFO: Request approved notification sent {
  "request_id": 123,
  "approver_id": 5,
  "requestor_email": "teacher@campus.test"
}
```

**Failure Log**:
```
[2024-02-15 13:45:23] local.ERROR: Failed to send notification {
  "request_id": 123,
  "error": "No recipients specified"
}
```

---

## Summary

The notification system provides:

✅ **Automatic notifications** on request approval, rejection, and completion
✅ **Event-driven architecture** for clean, testable code
✅ **Queued processing** for performance
✅ **Multiple recipients** based on role and department
✅ **Customizable templates** with Blade
✅ **Comprehensive logging** for debugging
✅ **Easy to extend** with new notification types
✅ **Production-ready** with error handling

**Status**: ✅ Complete and integrated
