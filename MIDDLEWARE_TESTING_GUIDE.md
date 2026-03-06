# Middleware Testing Guide

## Quick Start

### Test Credentials (from database seeder)
All users have password: `password`

```
Admin Account
Email: admin@campus.test
Department: Null (global scope)

Computer Science Department
- Teacher: teacher.cs1@campus.test
- HOD: hod.cs@campus.test

Finance Department
- Teacher: teacher.finance1@campus.test
- HOD: hod.finance@campus.test

Other Roles
- Principal: principal@campus.test
- Trust Head: trusthead@campus.test
- Provider: provider@campus.test
```

---

## Test Scenarios

### 1. CheckRole Middleware Tests

#### Test 1.1: Teacher Cannot Access Admin Panel
```
Steps:
1. Login as: teacher.cs1@campus.test / password
2. Navigate to: http://localhost:8000/admin/users
3. Expected: 403 Forbidden error page showing access denied
4. Log Check: storage/logs/laravel.log should show:
   - user_role: teacher
   - required_roles: admin
   - Unauthorized access attempt
```

#### Test 1.2: Admin Can Access Admin Panel
```
Steps:
1. Login as: admin@campus.test / password
2. Navigate to: http://localhost:8000/admin/users
3. Expected: Users list page loads successfully
4. Note: No 403 error
```

#### Test 1.3: Provider Cannot Create Requests
```
Steps:
1. Login as: provider@campus.test / password
2. Try to navigate to: http://localhost:8000/requests/create
3. Expected: 403 Forbidden (provider role not in teacher,hod,admin)
```

### 2. CheckDepartment Middleware Tests

#### Test 2.1: Non-Admin Without Department Gets 403
```
Prerequisites:
- Create a database record where a user has role != 'admin' AND department_id = NULL
  SELECT * FROM users WHERE role != 'admin' AND department_id IS NULL;

Steps:
1. Login as that user
2. Try to navigate any route requiring department check
3. Expected: 403 error with "Department not assigned" message

Alternative without creating bad data:
- This test would only occur with corrupted data, which shouldn't happen
  with normal seeding
```

#### Test 2.2: Admin Can Access Admin Routes Without Department
```
Background:
- Admin role always bypasses department check (global admin scope)

Steps:
1. Login as: admin@campus.test / password (has department_id = NULL)
2. Navigate to: http://localhost:8000/admin/users
3. Expected: Loads successfully (no 403)
```

### 3. CheckRequestAccess Middleware Tests

#### Test 3.1: Teacher Cannot Edit Another Teacher's Request
```
Steps:
1. Login as: teacher.cs1@campus.test / password
2. Create a stationary request (POST /requests)
3. Note the request ID
4. Logout and login as: teacher.finance1@campus.test / password
5. Try to access: http://localhost:8000/requests/{noted_id}/edit
6. Expected: 403 error "You don't have permission to edit this request"
7. Log Check: Should show:
   - user_id: finance_teacher_id
   - request_id: noted_id
   - current_status: pending
   - reason: "Request owned by different user"
```

#### Test 3.2: Teacher CAN View Another Teacher's Request (GET)
```
Steps:
1. Login as: teacher.cs1@campus.test / password
2. Create a request (POST /requests)
3. Logout and login as: teacher.finance1@campus.test / password
4. Navigate to: http://localhost:8000/requests/{request_id}
5. Expected: View request details (GET allowed for all)
6. Note: No edit button should be visible
```

#### Test 3.3: Teacher Cannot Edit Request After HOD Approval
```
Steps:
1. Login as: teacher.cs1@campus.test / password
2. Create a request
3. Logout and login as: hod.cs@campus.test / password
4. Access: http://localhost:8000/approvals/{request_id}
5. Approve the request (POST /approvals)
6. Logout and login as: teacher.cs1@campus.test / password
7. Try to access: http://localhost:8000/requests/{request_id}/edit
8. Expected: 403 error (request no longer pending)
```

#### Test 3.4: HOD Can View Department's Requests (GET Only)
```
Steps:
1. Login as: teacher.cs1@campus.test / password
2. Create multiple requests from CS department
3. Logout and login as: hod.cs@campus.test / password
4. Navigate to: http://localhost:8000/requests
5. Expected: Can see all CS department requests
6. Try to edit a request: http://localhost:8000/requests/{teacher_request_id}/edit
7. Expected: 403 (can't edit other teacher's request)
```

#### Test 3.5: HOD CAN Edit Own Pending Request
```
Steps:
1. Login as: hod.cs@campus.test / password
2. Create a request (HOD also has role:hod access)
3. Access: http://localhost:8000/requests/{own_request_id}/edit
4. Expected: Edit form loads successfully
5. Make changes and save
6. Expected: Update successful
```

#### Test 3.6: Principal Can View All Requests
```
Steps:
1. Login as: principal@campus.test / password
2. Navigate to: http://localhost:8000/requests
3. Expected: See all requests from all departments
4. Try to edit a request: http://localhost:8000/requests/{any_request_id}/edit
5. Expected: 403 (principal can view but not edit)
```

#### Test 3.7: Provider Can Only View Sent to Provider Requests
```
Steps:
1. Login as: hod.cs@campus.test / password
2. Create and approve a request through full workflow
3. Logout and login as: provider@campus.test / password
4. Navigate to: http://localhost:8000/requests
5. Expected: Only see requests with status "sent_to_provider"
6. Try to access other status requests: Should get 403
```

### 4. CheckApprovalAccess Middleware Tests

#### Test 4.1: HOD Cannot Approve Own Request
```
Steps:
1. Login as: hod.cs@campus.test / password
2. Create a request
3. Try to access: http://localhost:8000/approvals/{own_request_id}
4. Try to POST approval
5. Expected: 403 error "Cannot approve your own request"
6. Log Check: Should show "Cannot approve own request"
```

#### Test 4.2: HOD Cannot Approve Non-Pending Request
```
Prerequisites:
- Request must be in 'hod_approved' or later status

Steps:
1. Request exists in status: hod_approved
2. Login as: hod.cs@campus.test / password
3. Try to access: http://localhost:8000/approvals/{request_id}
4. Expected: 403 "Request must be in pending status for HOD approval"
```

#### Test 4.3: HOD Cannot Approve Other Department's Request
```
Steps:
1. Login as: teacher.finance1@campus.test / password
2. Create a request (Finance department)
3. Logout and login as: hod.cs@campus.test / password
4. Try to access: http://localhost:8000/approvals/{finance_request_id}
5. Expected: 403 "Request from different department"
```

#### Test 4.4: HOD Cannot Approve Same Request Twice (Idempotency)
```
Steps:
1. Request in pending status
2. Login as: hod.cs@campus.test / password
3. Approve the request (POST /approvals/{request_id})
4. Expected: Success, request moves to hod_approved
5. Try to approve again (POST /approvals/{request_id})
6. Expected: 403 "Already approved by HOD"
7. Log Check: Should show "Duplicate approval attempt"
```

#### Test 4.5: Principal Cannot Approve Pending Request
```
Steps:
1. Request in pending status
2. Login as: principal@campus.test / password
3. Try to access: http://localhost:8000/approvals/{request_id}
4. Expected: 403 "Request must be HOD approved for principal approval"
```

#### Test 4.6: Principal Can Approve HOD-Approved Request
```
Steps:
1. Request in hod_approved status
2. Login as: principal@campus.test / password
3. Access: http://localhost:8000/approvals/{request_id}
4. Approve the request
5. Expected: Success, request moves to principal_approved
```

#### Test 4.7: Principal Cannot Approve Twice
```
Steps:
1. Request in principal_approved status (already approved)
2. Login as: principal@campus.test / password
3. Try to approve again
4. Expected: 403 "Already approved by principal"
```

#### Test 4.8: TrustHead Can Approve Principal-Approved Request
```
Steps:
1. Request in principal_approved status
2. Login as: trusthead@campus.test / password
3. Access: http://localhost:8000/approvals/{request_id}
4. Approve the request
5. Expected: Success, request moves to trust_approved
```

#### Test 4.9: Admin Can Approve at Any Stage (Except Final)
```
Steps:
1. Request in pending status
2. Login as: admin@campus.test / password
3. Access: http://localhost:8000/approvals/{request_id}
4. Approve the request
5. Expected: Success (admin can skip levels)
6. Try with request in trust_approved status
7. Expected: Success
8. Try with request in sent_to_provider status
9. Expected: 403 (cannot modify final stages)
```

### 5. CheckProvider Middleware Tests

#### Test 5.1: Non-Provider Cannot Supply Request
```
Steps:
1. Request in sent_to_provider status
2. Login as: teacher.cs1@campus.test / password
3. Try to POST to: /requests/{request_id}/supplied
4. Expected: 403 "Only providers can perform this action"
5. Log Check: Should show user_role: teacher (not provider)
```

#### Test 5.2: Provider Can Supply Request
```
Prerequisites:
- Request must be in sent_to_provider status

Steps:
1. Request in sent_to_provider status
2. Login as: provider@campus.test / password
3. Access form to mark as supplied
4. Submit the supply form (POST /requests/{request_id}/supplied)
5. Expected: Success, request moves to completed
6. Note: Stock quantities are reduced
```

#### Test 5.3: Provider Cannot Supply Already Completed Request
```
Steps:
1. Request in completed status
2. Login as: provider@campus.test / password
3. Try to POST: /requests/{request_id}/supplied
4. Expected: 403 (request already completed)
```

### 6. Full Workflow Test (Integration)

#### Complete Approval Chain
```
Participants:
- teacher.cs1@campus.test (creates request)
- hod.cs@campus.test (HOD approves)
- principal@campus.test (Principal approves)
- trusthead@campus.test (Trust Head approves)
- admin@campus.test (Admin sends to provider)
- provider@campus.test (Provider supplies)

Steps:
1. [Teacher] Create request with 2-3 items
   - Assert: Request created with status "pending"

2. [HOD] Approve request
   - Navigate to: /approvals/{request_id}
   - Click approve
   - Assert: Request status = "hod_approved"

3. [Principal] Approve request
   - Navigate to: /approvals/{request_id}
   - Click approve
   - Assert: Request status = "principal_approved"

4. [Trust Head] Approve request
   - Navigate to: /approvals/{request_id}
   - Click approve
   - Assert: Request status = "trust_approved"

5. [Admin] Mark as sent to provider
   - Navigate to: /approvals/{request_id}
   - Approve (admin can process final stage)
   - Assert: Request status = "sent_to_provider"

6. [Provider] Mark as supplied
   - Navigate to: /requests
   - Find request in sent_to_provider status
   - Click "Mark as Supplied"
   - Assert: Request status = "completed"
   - Assert: Stock quantities reduced in products table
```

#### Rejection at Each Level
```
Scenario 1: HOD Rejects
1. [Teacher] Create request
2. [HOD] Navigate to /approvals/{request_id}
3. Fill remarks and click "Reject"
4. Assert: Request status = "rejected"
5. Assert: Teacher can no longer edit
6. Assert: Workflow stops

Scenario 2: Principal Rejects Hod-approved
1. [Teacher] Create and get HOD approval
2. [Principal] Navigate to /approvals/{request_id}
3. Click "Reject"
4. Assert: Request status = "rejected"
5. Assert: Request cycle stops
```

---

## Automated Testing Commands

### Run Laravel Tests
```bash
cd /home/suraj/Documents/CampusStoreManagementSystem

# Run all tests
php artisan test

# Run specific test class
php artisan test tests/Feature/Auth/AuthTest.php

# Run with verbose output
php artisan test --verbose

# Run single test method
php artisan test tests/Feature/RequestTest.php::test_teacher_cannot_edit_others_request
```

### Check Log Files
```bash
# Follow real-time logs
tail -f storage/logs/laravel.log

# See last 50 unauthorized attempts
grep "Unauthorized access attempt" storage/logs/laravel.log | tail -50

# See all middleware checks for specific user
grep "user_id.*5" storage/logs/laravel.log
```

### Database Queries
```bash
# Login to Laravel tinker
php artisan tinker

# Check request status
>>> $request = \App\Models\StationaryRequest::find(1);
>>> $request->status;

# Check approvals for request
>>> $request->approvals;

# Check user role
>>> $user = \App\Models\User::find(5);
>>> $user->role;
```

---

## Debugging Tips

### Enable Query Logging
In your test or route:
```php
\DB::enableQueryLog();
// ... your code ...
dd(\DB::getQueryLog());
```

### Add Test Logging
```php
// In middleware
\Log::debug('Debug point reached', [
    'step' => 'authorization_check',
    'user_id' => $user->id,
    'data' => $data
]);
```

### Use Laravel Debugbar
```bash
composer require barryvdh/laravel-debugbar --dev
```

Then check the bottom right panel in the browser for queries and logs.

---

## Expected Results Summary

| Test | Expected Outcome | Error Code |
|------|------------------|-----------|
| Teacher create request | ✅ Success | N/A |
| Teacher edit own request | ✅ Success (if pending) | N/A |
| Teacher edit other request | ❌ Forbidden | 403 |
| Teacher access admin panel | ❌ Forbidden | 403 |
| HOD approve own request | ❌ Forbidden | 403 |
| HOD approve other dept request | ❌ Forbidden | 403 |
| HOD approve twice | ❌ Forbidden | 403 |
| Principal approve pending | ❌ Forbidden | 403 |
| Principal approve HOD-approved | ✅ Success | N/A |
| Admin approve any stage | ✅ Success | N/A |
| Provider mark supplied (non-provider) | ❌ Forbidden | 403 |
| Provider mark supplied (as provider) | ✅ Success | N/A |

---

## Test Data Cleanup

After running tests, clean up generated data:

```bash
# Reset database to seeded state
php artisan migrate:fresh --seed

# Clear logs
rm -f storage/logs/laravel.log

# Clear cache
php artisan cache:clear
php artisan config:clear
```

---

**Status**: ✅ All tests ready to run. Start with scenario 1.1 for basic middleware testing.
