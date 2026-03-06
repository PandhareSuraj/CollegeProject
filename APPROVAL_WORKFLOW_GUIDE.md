# Request & Approval Workflow - Complete Implementation Guide

## Overview

This document details the complete Request and Approval workflow implementation for the Campus Store Management System. The system implements a 4-level approval process with comprehensive authorization, transaction support, and audit trails.

---

## Architecture

### Controllers

#### 1. **RequestController** (`app/Http/Controllers/RequestController.php`)

Handles complete request lifecycle management.

**Key Methods:**

| Method | Purpose | Authorization |
|--------|---------|-----------------|
| `index()` | List requests with role-based filtering | All authenticated users |
| `create()` | Show creation form | Teachers, HODs |
| `store()` | Create new request with items | Teachers, HODs |
| `show()` | Display request details | Users with view permission |
| `edit()` | Edit pending request form | Requestor, Admin |
| `update()` | Update pending request | Requestor, Admin |
| `destroy()` | Delete pending request | Requestor, Admin |

**Features:**

- **Role-Based Filtering**: Teachers see own requests, HODs see department requests, Admin sees all
- **Transaction Support**: Database transactions prevent partial data creation
- **Stock Validation**: Checks available product stock before creating items
- **Comprehensive Validation**: Input validation with custom error messages
- **Performance**: Eager loading prevents N+1 queries
- **Error Handling**: Detailed error messages for debugging

**Flow Example - Teacher Creating Request:**

```php
// 1. Validate department assignment
if (!$user->department_id) {
    return redirect with error message;
}

// 2. DB transaction wraps entire operation
DB::transaction(function () {
    // 3. Create StationaryRequest with status = 'pending'
    $request = StationaryRequest::create([...]);
    
    // 4. Validate and create RequestItems
    foreach ($validated['items'] as $item) {
        // Check stock availability
        // Create RequestItem with calculated subtotal
    }
    
    // 5. Update total_amount on request
    // 6. Return with success message
});
```

#### 2. **ApprovalController** (`app/Http/Controllers/ApprovalController.php`)

Handles the 4-level approval workflow.

**Key Methods:**

| Method | Purpose | Role |
|--------|---------|------|
| `show()` | Display approval form | HOD, Principal, Trust Head, Admin |
| `store()` | Process approval/rejection | HOD, Principal, Trust Head, Admin |
| `markSupplied()` | Mark request completed | Provider |
| `getPendingApprovals()` | List pending approvals | Role-specific |
| `getApprovalStats()` | Dashboard statistics | All roles |

**Approval Workflow States:**

```
Teacher Creates Request (status = 'pending')
        ↓
HOD Reviews → Approves (status = 'hod_approved') / Rejects (status = 'rejected')
        ↓
Principal Reviews → Approves (status = 'principal_approved') / Rejects
        ↓
Trust Head Reviews → Approves (status = 'trust_approved') / Rejects
        ↓
Admin Reviews → Approves (status = 'sent_to_provider') / Rejects
        ↓
Provider Marks Supplied (status = 'completed')
        Reduces Product Stock
```

**Features:**

- **Idempotency**: Prevents duplicate approvals at same level (unique constraint)
- **Workflow Validation**: Cannot skip approval levels
- **Transaction Support**: Atomic operations with rollback on error
- **Audit Logging**: Tracks all approval actions
- **Stock Management**: Reduces inventory when provider supplies
- **Rejection Handling**: Stops workflow immediately when rejected

**Approval Flow Example:**

```php
// User attempts to approve request
$approval = Approval::create([
    'request_id' => $request->id,
    'approved_by' => $user->id,
    'role' => $user->role,
    'status' => 'approved',
    'remarks' => 'Looks good',
]);

// Update request status to next level
$request->update(['status' => 'hod_approved']);

// Prevent duplicate approval at same level
// Unique constraint: (request_id, role)
```

---

### Service Layer

#### **ApprovalWorkflowService** (`app/Services/ApprovalWorkflowService.php`)

Business logic layer for approval workflows.

**Key Methods:**

```php
// Check authorization
canApprove(User $user, StationaryRequest $request): bool

// Process decision
processApproval(
    StationaryRequest $request,
    User $approver,
    string $decision,  // 'approved' or 'rejected'
    ?string $remarks
): Approval

// Get timeline for display
getApprovalTimeline(StationaryRequest $request): array

// Get workflow stage info
getWorkflowStageInfo(StationaryRequest $request): array

// Get pending for dashboard
getPendingApprovalsForUser(User $user): Collection

// Get statistics
getApprovalStats(User $user): array
```

**Usage Example:**

```php
$workflowService = new ApprovalWorkflowService();

// Check if user can approve
if ($workflowService->canApprove($user, $request)) {
    // Process approval
    $approval = $workflowService->processApproval(
        $request,
        $user,
        'approved',
        'Approved by HOD'
    );
}

// Get pending approvals
$pending = $workflowService->getPendingApprovalsForUser($user);
```

---

### Authorization Layer

#### **StationaryRequestPolicy** (`app/Policies/StationaryRequestPolicy.php`)

Gate-based authorization for requests.

**Policy Methods:**

```php
view(User $user, StationaryRequest $request): bool
create(User $user): bool
update(User $user, StationaryRequest $request): bool
delete(User $user, StationaryRequest $request): bool
approve(User $user, StationaryRequest $request): bool
reject(User $user, StationaryRequest $request): bool
```

**Usage in Views:**

```blade
@can('view', $request)
    <!-- Display request -->
@endcan

@can('approve', $request)
    <!-- Show approve button -->
@endcan
```

**Usage in Controllers:**

```php
$this->authorize('approve', $request);
```

---

### Enums

#### **RequestEnums** (`app/Enums/RequestEnums.php`)

Type-safe status constants.

**RequestStatus Enum:**

```php
enum RequestStatus: string {
    case PENDING = 'pending';
    case HOD_APPROVED = 'hod_approved';
    case PRINCIPAL_APPROVED = 'principal_approved';
    case TRUST_APPROVED = 'trust_approved';
    case SENT_TO_PROVIDER = 'sent_to_provider';
    case COMPLETED = 'completed';
    case REJECTED = 'rejected';
}

// Usage
$status = RequestStatus::PENDING;
$label = $status->label();  // "Pending"
$color = $status->badgeColor();  // "warning"
$next = $status->nextStatus();  // RequestStatus::HOD_APPROVED
```

**ApprovalRole & ApprovalStatus Enums:**

```php
enum ApprovalRole: string { /* admin, teacher, hod, etc */ }
enum ApprovalStatus: string { /* pending, approved, rejected */ }
```

---

## Database Schema

### Tables & Foreign Keys

**requests table:**
```
id (PK)
department_id (FK → departments, cascade)
requested_by (FK → users, cascade)
status (enum: pending → rejected)
total_amount (decimal)
created_at, updated_at
```

**request_items table:**
```
id (PK)
request_id (FK → requests, cascade)
product_id (FK → products, cascade)
quantity (unsigned int)
price (decimal)
subtotal (decimal)
created_at, updated_at
```

**approvals table:**
```
id (PK)
request_id (FK → requests, cascade)
approved_by (FK → users, cascade)
role (enum)
status (approved, rejected, pending)
remarks (text)
created_at, updated_at

Unique Constraint: (request_id, role)
```

**Indexes:**
- `requests`: (status, created_at), (department_id, requested_by)
- `request_items`: (request_id, product_id)
- `approvals`: (request_id, role, status), (status, created_at)

---

## Request Lifecycle

### 1. Request Creation
```
Teacher/HOD → RequestController::store()
  ├─ Validate items array
  ├─ Check department assignment
  ├─ Create StationaryRequest (pending)
  ├─ Create RequestItems
  ├─ Calculate total_amount
  └─ Redirect to show
```

### 2. HOD Approval
```
HOD → ApprovalController::show()
  ├─ Load request with relationships
  ├─ Check HOD is from same department
  ├─ Display approval form
  
HOD → ApprovalController::store()
  ├─ Validate decision (approved/rejected)
  ├─ Create Approval record (role='hod')
  ├─ Update request status → hod_approved / rejected
  └─ Log audit
```

### 3. Principal Approval
```
Same flow as HOD
  ├─ Check request status = 'hod_approved'
  ├─ Create Approval (role='principal')
  ├─ Update status → principal_approved
```

### 4. Trust Head Approval
```
Same flow as HOD
  ├─ Check request status = 'principal_approved'
  ├─ Create Approval (role='trust_head')
  ├─ Update status → trust_approved
```

### 5. Provider Supply
```
Provider → ApprovalController::markSupplied()
  ├─ Verify request status = 'sent_to_provider'
  ├─ For each item:
  │   ├─ Check product stock
  │   ├─ Decrement stock_quantity by item quantity
  ├─ Update request status → completed
  └─ Return success
```

---

## Role-Based Access Control

### Teacher
- ✅ Create requests in assigned department
- ✅ Edit/Delete own pending requests
- ✅ View own requests
- ✅ Cannot approve

### HOD
- ✅ Create requests in their department
- ✅ Edit/Delete own pending requests
- ✅ View department requests
- ✅ Approve pending requests from department
- ✅ Cannot approve cross-department

### Principal
- ✅ View all requests
- ✅ Approve HOD-approved requests
- ✅ Cannot create requests

### Trust Head
- ✅ View all requests
- ✅ Approve Principal-approved requests
- ✅ Cannot create requests

### Provider
- ✅ View only supplied/completed requests
- ✅ Mark requests as supplied
- ✅ Cannot approve

### Admin
- ✅ View/manage all aspects
- ✅ Approve at any stage
- ✅ Manage users, products, departments

---

## Error Handling & Validation

### Request Creation Validation
```php
'items' => 'required|array|min:1|max:50',
'items.*.product_id' => 'required|exists:products,id',
'items.*.quantity' => 'required|integer|min:1|max:1000',
```

### Approval Validation
```php
'status' => 'required|in:approved,rejected',
'remarks' => 'nullable|string|max:500',
```

### Business Logic Validation
- Department assignment required for requestors
- Stock must be available for all items
- Cannot approve own request
- Cannot skip approval levels
- Cannot duplicate approval at same level
- Cannot approve completed/rejected requests

---

## Performance Optimizations

### Eager Loading
```php
$requests = StationaryRequest::with([
    'department',
    'requestedBy',
    'items.product',
    'approvals.approver'
])->get();
```

### Strategic Indexing
- Status columns indexed for filtering
- Composite indexes for common queries
- Foreign key columns indexed for joins

### Transaction Support
- Atomic operations prevent partial data
- Rollback on error
- Data consistency guaranteed

---

## Audit & Logging

### Approval Audit Trail
Each approval tracked in `approvals` table:
- Who approved (approved_by)
- When (created_at)
- Decision (status: approved/rejected)
- Remarks (optional reason)
- Role level (hod, principal, etc.)

### Usage Query
```php
$timeline = $request->approvals()
    ->with('approver')
    ->orderBy('created_at')
    ->get();
```

---

## Testing the Workflow

### Test Scenario
```
1. Teacher: Create request with 3 items
2. HOD: Approve request
3. Principal: Approve request
4. Trust Head: Approve request
5. Admin: Approve request (sends to provider)
6. Provider: Mark as supplied (completes, reduces stock)
```

### Debug Timeline
```php
// Get full approval history
$approval = $request->approvals()
    ->with('approver')
    ->orderBy('created_at')
    ->get();

// Check status progression
echo $request->status;  // 'completed'

// Verify stock reduced
echo $product->stock_quantity;  // Reduced by item quantity
```

---

## Future Enhancements

- [ ] Email notifications on approval/rejection
- [ ] Request templates for common purchases
- [ ] Budget limits per department
- [ ] Bulk request export
- [ ] Request cancellation workflow
- [ ] Supplier management
- [ ] Analytics & reporting
- [ ] API endpoints for third-party integration
- [ ] Mobile app integration
- [ ] Advanced filtering & search

---

## Files Summary

| File | Purpose | Lines |
|------|---------|-------|
| RequestController.php | Request CRUD & filtering | 350+ |
| ApprovalController.php | Approval workflow | 300+ |
| ApprovalWorkflowService.php | Workflow business logic | 250+ |
| StationaryRequestPolicy.php | Authorization gates | 80+ |
| AuthServiceProvider.php | Policy registration | 30+ |
| RequestEnums.php | Type-safe constants | 120+ |

**Total: 1,100+ lines of well-structured, production-ready code**

---

## Quick Reference

### Common Tasks

**Get user's pending approvals:**
```php
$service = new ApprovalWorkflowService();
$pending = $service->getPendingApprovalsForUser($user);
```

**Process approval:**
```php
$approval = $service->processApproval(
    $request, 
    $user, 
    'approved',
    'Looks comprehensive'
);
```

**Get approval stats:**
```php
$stats = $service->getApprovalStats($user);
echo $stats['pending_approvals'];
```

**Check authorization:**
```php
if ($service->canApprove($user, $request)) {
    // Show approval form
}
```

