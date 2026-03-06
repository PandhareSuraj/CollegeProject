# Quick Setup: Notification System

## 🎯 What Was Added

A complete event-driven notification system that automatically sends emails when:
- Requests are approved ✅
- Requests are rejected ✅  
- Items are supplied (completed) ✅

## 📁 Files Created/Modified

### Events (3 files created)
- `app/Events/RequestApproved.php`
- `app/Events/RequestRejected.php`
- `app/Events/RequestSupplied.php`

### Listeners (3 files created)
- `app/Listeners/SendRequestApprovedNotification.php`
- `app/Listeners/SendRequestRejectedNotification.php`
- `app/Listeners/SendRequestSuppliedNotification.php`

### Mailables (3 files created)
- `app/Mail/RequestApprovedNotification.php`
- `app/Mail/RequestRejectedNotification.php`
- `app/Mail/RequestSuppliedNotification.php`

### Email Templates (3 files created)
- `resources/views/mail/request-approved.blade.php`
- `resources/views/mail/request-rejected.blade.php`
- `resources/views/mail/request-supplied.blade.php`

### Files Modified (2 files)
- `app/Providers/AppServiceProvider.php` - Added event listener registration
- `app/Http/Controllers/ApprovalController.php` - Added event dispatches

### Documentation (1 file created)
- `NOTIFICATION_SYSTEM_GUIDE.md` - Complete notification system guide

---

## ⚙️ Configuration Required

### 1. Mail Configuration (.env)

Add or update your mail configuration:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@campusstore.test
MAIL_FROM_NAME="Campus Store Management"
```

**For Local Testing**: Use `MAIL_MAILER=log` to log emails to storage/logs/laravel.log

### 2. Queue Configuration (.env)

```env
QUEUE_CONNECTION=database
```

This uses the database for queueing. Other options: `redis`, `beanstalkd`, `sync` (immediate)

### 3. Database Setup (for queue)

Ensure jobs/failed_jobs tables exist:

```bash
php artisan queue:table
php artisan migrate
```

---

## 🚀 Testing the Notification System

### Test 1: Approve a Request and Check Email

```bash
# 1. Start queue worker
php artisan queue:work

# 2. In another terminal, log in and approve a request
# - Dashboard → Find pending request → Approve

# 3. Check for email
# - If MAIL_MAILER=log: Check storage/logs/laravel.log
# - If using Mailtrap: Check Mailtrap inbox
```

### Test 2: Test Mail Configuration

```bash
php artisan tinker

>>> use Illuminate\Mail\Message;
>>> Mail::to('test@example.com')->send(new Message);
// Should show no errors
```

### Test 3: Manual Trigger

```php
// In tinker
$request = App\Models\StationaryRequest::first();
$approval = App\Models\Approval::first();

// Send approval notification
Mail::to('teacher@campus.test')->send(
    new App\Mail\RequestApprovedNotification($request, $approval)
);

// Check logs
tail -f storage/logs/laravel.log
```

---

## 📧 Email Recipients

### On Request Approval
- ✅ Requestor (person who created the request)
- ✅ Next-level approvers
  - After HOD approval → Principal notified
  - After Principal approval → Trust Head notified
  - After Trust Head approval → Admin notified
- ✅ Department HOD (optional tracking)

### On Request Rejection
- ✅ Requestor
- ✅ Department HOD
- ✅ Admin (for tracking)

### On Request Supply (Completion)
- ✅ Requestor
- ✅ Department HOD
- ✅ Admin

---

## 🔧 Advanced Configuration

### Using Mailtrap for Development

1. Sign up at https://mailtrap.io
2. Create a new inbox
3. Copy credentials to `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=dev@campusstore.test
```

### Using Production SMTP

For Gmail:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=465
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=ssl
```

For SendGrid:
```env
MAIL_MAILER=sendgrid
SENDGRID_API_KEY=your-sendgrid-api-key
```

---

## 🐛 Troubleshooting

### Emails Not Sending

**Step 1**: Verify mail configuration
```bash
php artisan config:show mail
```

**Step 2**: Check Laravel logs
```bash
tail -f storage/logs/laravel.log
```

**Step 3**: Check failed jobs
```bash
php artisan queue:failed
```

**Step 4**: Test SMTP connection
```bash
php artisan tinker
>>> Mail::to('test@example.com')->send(new \Illuminate\Mail\Message);
```

### Queue Not Processing

**Step 1**: Start queue worker
```bash
php artisan queue:work
```

**Step 2**: Check queue configuration
```php
// .env should have
QUEUE_CONNECTION=database
```

**Step 3**: Ensure jobs table exists
```bash
php artisan migrate
```

---

## 📋 Workflow Example

### Complete Flow with Notifications

```
1. Teacher creates request for office supplies
   └─ No notification sent

2. HOD approves request
   └─ Email sent to: Teacher, Principal
   └─ Request moves to "hod_approved"

3. Principal approves request
   └─ Email sent to: Teacher, Trust Head, HOD
   └─ Request moves to "principal_approved"

4. Trust Head approves request
   └─ Email sent to: Teacher, Admin, HOD
   └─ Request moves to "trust_approved"

5. Admin sends to provider
   └─ Email sent to: Teacher, Provider, HOD
   └─ Request moves to "sent_to_provider"

6. Provider supplies items
   └─ Email sent to: Teacher, HOD, Admin
   └─ Request moves to "completed"
   └─ Stock quantities reduced

OR at any stage:

X. Approver rejects request
   └─ Email sent to: Teacher, HOD, Admin
   └─ Request moves to "rejected"
   └─ Includes rejection reason in email
```

---

## 📚 Full Documentation

For complete documentation including:
- Architecture details
- Event/Listener patterns
- Email template customization
- Performance optimization
- Advanced usage examples

See: [`NOTIFICATION_SYSTEM_GUIDE.md`](NOTIFICATION_SYSTEM_GUIDE.md)

---

## ✅ Verification Checklist

- [ ] Mail configuration added to `.env`
- [ ] Queue configuration set in `.env`
- [ ] `php artisan migrate` executed (creates jobs table)
- [ ] All files syntax validated (✅ verified)
- [ ] Event listeners registered (✅ in AppServiceProvider)
- [ ] ApprovalController updated with event dispatches (✅ verified)
- [ ] Email templates created (✅ 3 templates)
- [ ] Queue worker can be started: `php artisan queue:work`
- [ ] Test approval workflow to trigger notifications

---

## 🎯 Next Steps

1. Configure mail in `.env` (required)
2. Run migrations: `php artisan migrate`
3. Start queue worker: `php artisan queue:work`
4. Test by approving a request and checking logs

**Status**: ✅ **Notification System Ready**

For questions or customization, see `NOTIFICATION_SYSTEM_GUIDE.md`
