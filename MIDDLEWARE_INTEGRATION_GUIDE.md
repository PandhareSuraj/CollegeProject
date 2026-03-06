# Middleware Security Layer - Integration Complete

## Overview

The Campus Store Management System now includes a comprehensive 5-layer middleware security system providing defense-in-depth authorization at the route level, request level, and workflow level.

## Middleware Stack

### 1. CheckRole Middleware
**File**: `app/Http/Middleware/CheckRole.php`  
**Purpose**: Validate user has required role(s)  
**Usage**: `Route::middleware('role:teacher,hod,admin')`

```php
// Example: Only teachers can create requests
Route::middleware('role:teacher,hod,admin')->post('/requests', [RequestController::class, 'store']);
```

**Authorization Rules**:
- User role must be in the required roles array
- Admin always has access (can bypass in special cases)
- Logs all unauthorized attempts with context

**Logs**: user_id, user_role, required_roles, requested_url, ip_address, timestamp

---

### 2. CheckDepartment Middleware
**File**: `app/Http/Middleware/CheckDepartment.php`  
**Purpose**: Validate user has department assigned  
**Usage**: Applied to admin routes group

```php
// Example: Admin routes require department assignment
Route::middleware(['role:admin', 'department'])->group(function () {
    Route::resource('users', UserController::class);
});
```

**Authorization Rules**:
- User must have department_id assigned
- Admin role bypasses check (global scope)
- Provider role bypasses check (not department-specific)
- Teacher/HOD/Principal/TrustHead require department

**Logs**: user_id, user_role, department_id status

---

### 3. CheckRequestAccess Middleware
**File**: `app/Http/Middleware/CheckRequestAccess.php`  
**Purpose**: Resource-level access control with HTTP method validation  
**Usage**: Applied to entire request routes group

```php
// Example: All request routes validate ownership/department
Route::middleware(['auth', 'verified', 'check-request'])->group(function () {
    Route::get('/requests', [RequestController::class, 'index']);
    Route::get('/requests/{stationaryRequest}', [RequestController::class, 'show']);
    Route::put('/requests/{stationaryRequest}', [RequestController::class, 'update']);
});
```

**Authorization Rules** (for modification operations: POST, PUT, DELETE):
- Teacher: Only own requests in pending status
- HOD: Only own requests in pending status, OR view any department request (GET only)
- Principal: View all requests
- TrustHead: View all requests
- Admin: All access
- Provider: View only sent_to_provider and completed requests

**Authorization Rules** (for view operations: GET):
- All roles: Can view based on their scope
- No method restrictions on GET

**Logs**: user_id, user_role, request_id, request_status, http_method, requested_scope

---

### 4. CheckApprovalAccess Middleware
**File**: `app/Http/Middleware/CheckApprovalAccess.php`  
**Purpose**: Validate approval workflow eligibility  
**Usage**: Applied to approval decision routes

```php
// Example: Approval operations require workflow validation
Route::middleware(['role:hod,principal,trust_head,admin', 'check-approval'])->group(function () {
    Route::post('/approvals/{stationaryRequest}', [ApprovalController::class, 'store']);
});
```

**Authorization Rules**:
- No user can approve their own request
- No approvals allowed on completed/rejected requests

**By Role**:
- HOD: Request in 'pending' status only, same department, no prior HOD approval
- Principal: Request in 'hod_approved' status only, no prior principal approval
- TrustHead: Request in 'principal_approved' status only, no prior trust head approval
- Admin: Any status except final (sent_to_provider, completed, rejected)

**Logs**: user_id, user_role, request_id, current_status, previous_approvals, decision_outcome

---

### 5. CheckProvider Middleware
**File**: `app/Http/Middleware/CheckProvider.php`  
**Purpose**: Provider-only access gate  
**Usage**: Applied to provider supply operations

```php
// Example: Only providers can mark requests as supplied
Route::middleware(['check-provider'])->group(function () {
    Route::post('/requests/{stationaryRequest}/supplied', [ApprovalController::class, 'markSupplied']);
});
```

**Authorization Rules**:
- User must have provider role
- Validates user->isProvider() is true

**Logs**: user_id, user_role, attempted_action, outcome

---

## Route Protection Configuration

### Request Routes
```php
Route::middleware(['auth', 'verified', 'check-request'])->group(function () {
    // Additional role middleware on modification routes
    Route::middleware('role:teacher,hod,admin')->group(function () {
        Route::get('/requests/create', ...);
        Route::post('/requests', ...);
        Route::get('/requests/{id}/edit', ...);
        Route::put('/requests/{id}', ...);
        Route::delete('/requests/{id}', ...);
    });
    
    // View routes (all roles)
    Route::get('/requests', ...);
    Route::get('/requests/{id}', ...);
});
```

### Approval Routes
```php
Route::middleware(['auth', 'verified'])->group(function () {
    // Approval decision routes
    Route::middleware('role:hod,principal,trust_head,admin')->group(function () {
        Route::middleware('check-approval')->group(function () {
            Route::get('/approvals/{id}', ...);
            Route::post('/approvals/{id}', ...);
        });
    });
    
    // Provider supply routes
    Route::middleware('check-provider')->group(function () {
        Route::post('/requests/{id}/supplied', ...);
    });
});
```

### Admin Routes
```php
Route::prefix('admin')
    ->middleware(['auth', 'verified', 'role:admin', 'department'])
    ->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('departments', DepartmentController::class);
        Route::resource('products', ProductController::class);
        Route::get('/dashboard', ...);
    });
```

---

## Authorization Decision Flow

```
User makes request
    ↓
[1] Auth Middleware: Authenticated?
    ↓ No → Redirect to login
    ↓ Yes → Continue
    
[2] CheckRole Middleware: Has required role?
    ↓ No → 403 Forbidden
    ↓ Yes → Continue
    
[3] CheckDepartment Middleware (admin): Has department?
    ↓ No → 403 Forbidden
    ↓ Yes → Continue
    
[4] CheckRequestAccess Middleware (on request routes): Can access resource?
    ↓ No → 403 Forbidden
    ↓ Yes → Continue
    
[5] CheckApprovalAccess Middleware (on approval routes): Approval valid?
    ↓ No → 403 Forbidden
    ↓ Yes → Continue
    
[6] CheckProvider Middleware (on supply routes): Is provider?
    ↓ No → 403 Forbidden
    ↓ Yes → Continue
    
[7] Controller Authorization: Policy checks
    ↓ Fail → 403 Forbidden
    ↓ Pass → Execute action
    
[8] Controller Business Logic: Service layer validation
    ↓ Fail → 422 Unprocessable Entity
    ↓ Pass → Success response
```

---

## Error Handling

### 403 Forbidden View
**File**: `resources/views/errors/403.blade.php`

Displays:
- User's current role
- User's department (if assigned)
- Back button to previous page
- Link to dashboard

**Triggered When**:
- CheckRole middleware fails
- CheckDepartment middleware fails
- CheckRequestAccess middleware fails
- CheckApprovalAccess middleware fails
- CheckProvider middleware fails
- Policy authorization fails

### 401 Unauthorized View
**File**: `resources/views/errors/401.blade.php`

Displays:
- Login link
- Home page link

**Triggered When**:
- User not authenticated
- Session expired

---

## Logging & Audit Trail

All middleware logs unauthorized access attempts to `storage/logs/laravel.log`:

```
[2024-01-15 10:23:45] local.WARNING: Unauthorized access attempt
{
    "user_id": 5,
    "user_role": "teacher",
    "required_roles": "hod,principal,admin",
    "requested_url": "http://campus.test/requests/123/edit",
    "ip_address": "192.168.1.100",
    "user_agent": "Mozilla/5.0...",
    "timestamp": "2024-01-15T10:23:45.123Z"
}
```

**Log Locations by Middleware**:
- CheckRole: Full authorization failure details
- CheckDepartment: Department assignment status
- CheckRequestAccess: Request ownership/access scope
- CheckApprovalAccess: Workflow validation details
- CheckProvider: Provider status validation

---

## Testing the Middleware

### Test 1: Role-Based Access
```bash
# Login as teacher
# Try to access /admin/users → Should get 403

# Login as admin
# Access /admin/users → Should work
```

### Test 2: Request Access Control
```bash
# Teacher 1 creates request
# Teacher 2 tries to edit Teacher 1's request → Should get 403

# HOD of same department views Teacher 1's request → Should work (GET only)
# HOD tries to edit Teacher 1's request → Should get 403
```

### Test 3: Approval Workflow
```bash
# HOD approves request → No error
# HOD tries to approve same request again → 403 (already approved)
# Principal tries to approve pending request → 403 (needs HOD approval first)
# Principal approves HOD-approved request → No error
```

### Test 4: Provider Access
```bash
# Non-provider tries to mark request as supplied → 403
# Provider marks request as supplied → Works
```

### Test 5: Department Validation
```bash
# Admin without department_id trying to access /admin/users → 403
# Admin with department_id accessing /admin/users → Works
```

---

## Performance Considerations

### Middleware Order Impact
1. **auth, verified**: Laravel built-in (fast, caches session)
2. **check-request**: Queries request by ID (indexed by primary key)
3. **check-approval**: Queries request + approval history (indexed by request_id, role)
4. **role**: Simple string comparison (fastest)
5. **department**: Simple comparison with user->department_id (fast)
6. **check-provider**: Simple string comparison (fastest)

### Database Indexes Used
- `requests(id)`: Primary key - used for request lookup
- `approvals(request_id)`: Foreign key - used for checking prior approvals
- `approvals(request_id, role)`: Composite - used for idempotency check

### Optimization Tips
1. Keep role arrays to 2-3 values maximum
2. Order middleware: fast checks first (role), slow checks last (check-request)
3. Use route groups to avoid repeated middleware
4. Cache role-based permissions if checking many times per request

---

## Middleware Registration

All middleware are registered in `bootstrap/app.php`:

```php
$middleware->alias([
    'role' => \App\Http\Middleware\CheckRole::class,
    'department' => \App\Http\Middleware\CheckDepartment::class,
    'check-request' => \App\Http\Middleware\CheckRequestAccess::class,
    'check-approval' => \App\Http\Middleware\CheckApprovalAccess::class,
    'check-provider' => \App\Http\Middleware\CheckProvider::class,
]);
```

---

## Best Practices

### 1. Defense in Depth
Always use multiple layers:
```php
// Good: Multiple layers
Route::middleware(['auth', 'verified', 'role:admin', 'check-request'])
    ->put('/requests/{id}', ...)

// Avoid: Relying on single layer
Route::middleware('role:admin')->put('/requests/{id}', ...)
```

### 2. Group Related Routes
```php
// Good: Grouping reduces redundancy
Route::middleware(['auth', 'verified'])->group(function () {
    Route::middleware('check-request')->group(function () {
        Route::get('/requests', ...);
        Route::post('/requests', ...);
    });
});

// Avoid: Repeating middleware on each route
Route::middleware(['auth', 'verified', 'check-request'])->get('/requests', ...);
Route::middleware(['auth', 'verified', 'check-request'])->post('/requests', ...);
```

### 3. Order Middleware Efficiently
```php
// Good: Cheapest checks first
Route::middleware(['role:admin', 'department', 'check-request'])->put(...)

// Why: If role check fails, expensive check-request never runs
```

### 4. Log with Context
Middleware automatically logs:
- What was checked (roles, permissions)
- Why it failed
- Who attempted access (user_id, IP)
- When (timestamp)

Use logs to:
- Detect unauthorized access patterns
- Debug authorization issues
- Audit compliance

---

## Customization Guide

### Adding New Middleware
1. Create middleware file: `app/Http/Middleware/CheckCustom.php`
2. Register in `bootstrap/app.php`:
   ```php
   'custom' => \App\Http\Middleware\CheckCustom::class,
   ```
3. Apply to routes:
   ```php
   Route::middleware('custom')->group(...)
   ```

### Modifying Authorization Rules
Edit specific middleware file's `canAccess()` method:
- CheckRole: Modify role array logic
- CheckRequestAccess: Modify ownership/scope logic
- CheckApprovalAccess: Modify workflow status logic

### Adding Role
1. Add to database with migration
2. Add method to User model: `public function isNewRole() { ... }`
3. Add cases to middleware authorization checks
4. Update authorization matrix in documentation

---

## Troubleshooting

### Problem: 403 on every request
**Solution**: Check middleware alias registration in bootstrap/app.php

### Problem: Middleware not applying
**Solution**: Verify route middleware syntax: `Route::middleware(['name'])->group()`

### Problem: Authorization logic incorrect
**Solution**: Review middleware `canAccess()` method and add logging:
```php
Log::debug('Authorization check', [
    'user_id' => $user->id,
    'result' => $canAccess,
]);
```

### Problem: Performance degradation
**Solution**: 
1. Check database indexes exist
2. Add query logging: `DB::enableQueryLog()` in middleware
3. Profile with Clockwork extension
4. Consider caching role permissions

---

## Summary

The middleware security layer provides:
- ✅ Route-level access control (CheckRole)
- ✅ Department validation (CheckDepartment)
- ✅ Resource-level access (CheckRequestAccess)
- ✅ Workflow validation (CheckApprovalAccess)
- ✅ Specialized access gates (CheckProvider)
- ✅ Comprehensive logging and audit trails
- ✅ Error views with helpful messages
- ✅ Defense-in-depth architecture
- ✅ Performance optimized with indexes
- ✅ Easy to test and customize

**Status**: ✅ Complete and ready for testing
