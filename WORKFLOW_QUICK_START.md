# Request & Approval Workflow - Quick Start Guide

## Overview

This guide helps developers quickly integrate and use the request and approval workflow system in their code.

---

## 🎯 Common Tasks

### 1. Creating a Request (Teacher/HOD)

**Controller:**
```php
// app/Http/Controllers/RequestController.php
Route::post('/requests', [RequestController::class, 'store']);
```

**Form Data:**
```json
{
  "items": [
    { "product_id": 1, "quantity": 5 },
    { "product_id": 2, "quantity": 3 },
    { "product_id": 5, "quantity": 10 }
  ]
}
```

**Blade Template:**
```blade
<form action="{{ route('requests.store') }}" method="POST">
    @csrf
    <div id="items-container">
        <div class="item-row">
            <select class="product-select" name="items[0][product_id]" required>
                <option>Select Product</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                            data-price="{{ $product->price }}">
                        {{ $product->name }} (₹{{ $product->price }})
                    </option>
                @endforeach
            </select>
            <input type="number" class="quantity-input" 
                   name="items[0][quantity]" min="1" required>
        </div>
    </div>
    <button type="submit">Create Request</button>
</form>
```

---

### 2. Displaying Request with Workflow Status

**Controller:**
```php
$request = StationaryRequest::with([
    'department',
    'requestedBy',
    'items.product',
    'approvals.approver'
])->findOrFail($id);

return view('requests.show', compact('request'));
```

**Blade Template:**
```blade
<div class="request-details">
    <h3>Request #{{ $request->id }}</h3>
    
    <!-- Status Badge -->
    <span class="badge badge-{{ $request->status === 'completed' ? 'success' : 'warning' }}">
        {{ ucfirst(str_replace('_', ' ', $request->status)) }}
    </span>
    
    <!-- Items Table -->
    <table>
        @foreach($request->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹{{ $item->price }}</td>
                <td>₹{{ $item->subtotal }}</td>
            </tr>
        @endforeach
    </table>
    
    <!-- Approval Timeline -->
    <div class="approval-timeline">
        @foreach($request->approvals as $approval)
            <div class="approval-item">
                <strong>{{ $approval->approver->name }}</strong> 
                ({{ ucfirst(str_replace('_', ' ', $approval->role)) }})
                <span class="badge badge-{{ $approval->status === 'approved' ? 'success' : 'danger' }}">
                    {{ ucfirst($approval->status) }}
                </span>
                <p>{{ $approval->remarks }}</p>
                <small>{{ $approval->created_at->format('M d, Y H:i') }}</small>
            </div>
        @endforeach
    </div>
</div>
```

---

### 3. Showing Approval Form

**Controller:**
```php
public function show(StationaryRequest $request)
{
    $user = Auth::user();
    
    // Check authorization
    $this->authorize('approve', $request);
    
    $request->load([
        'department',
        'requestedBy',
        'items.product',
        'approvals.approver'
    ]);
    
    return view('approvals.show', compact('request', 'user'));
}
```

**Blade Template:**
```blade
<div class="approval-form">
    <h3>Approve Request #{{ $request->id }}</h3>
    
    <!-- Request Details (Read-only) -->
    <table class="request-items">
        @foreach($request->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>₹{{ $item->subtotal }}</td>
            </tr>
        @endforeach
    </table>
    
    <!-- Approval Form -->
    <form action="{{ route('approvals.store', $request) }}" method="POST">
        @csrf
        
        <div class="form-group">
            <label>Decision</label>
            <input type="radio" name="status" value="approved" required> Approve
            <input type="radio" name="status" value="rejected" required> Reject
        </div>
        
        <div class="form-group">
            <label>Remarks (Optional)</label>
            <textarea name="remarks" maxlength="500"></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit Decision</button>
    </form>
</div>
```

---

### 4. Processing Approval

**Controller:**
```php
public function store(Request $request, StationaryRequest $stationaryRequest)
{
    $user = Auth::user();
    
    // Validate authorization
    $this->authorize('approve', $stationaryRequest);
    
    // Validate input
    $validated = $request->validate([
        'status' => 'required|in:approved,rejected',
        'remarks' => 'nullable|string|max:500',
    ]);
    
    try {
        DB::transaction(function () use ($user, $stationaryRequest, $validated) {
            // Create approval record
            $approval = Approval::create([
                'request_id' => $stationaryRequest->id,
                'approved_by' => $user->id,
                'role' => $user->role,
                'status' => $validated['status'],
                'remarks' => $validated['remarks'] ?? null,
            ]);
            
            // Update request status
            if ($validated['status'] === 'rejected') {
                $stationaryRequest->update(['status' => 'rejected']);
            } else {
                // Get next status
                $nextStatus = match($user->role) {
                    'hod' => 'hod_approved',
                    'principal' => 'principal_approved',
                    'trust_head' => 'trust_approved',
                    'admin' => 'sent_to_provider',
                };
                $stationaryRequest->update(['status' => $nextStatus]);
            }
        });
        
        return redirect()->route('dashboard')
            ->with('success', 'Request ' . $validated['status']);
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error: ' . $e->getMessage());
    }
}
```

---

### 5. Using the Workflow Service

**In Controller:**
```php
use App\Services\ApprovalWorkflowService;

class DashboardController extends Controller
{
    public function index(ApprovalWorkflowService $workflowService)
    {
        $user = Auth::user();
        
        // Get pending approvals
        $pending = $workflowService->getPendingApprovalsForUser($user);
        
        // Get approval stats
        $stats = $workflowService->getApprovalStats($user);
        
        // Get workflow info for a request
        $workflowInfo = $workflowService->getWorkflowStageInfo($request);
        
        return view('dashboard', compact('pending', 'stats', 'workflowInfo'));
    }
}
```

---

### 6. Checking Authorization

**In Controller:**
```php
use Illuminate\Support\Facades\Gate;

// Method 1: Using policy
$this->authorize('approve', $request);

// Method 2: Using gate
if (Gate::denies('approve-request', $request)) {
    abort(403);
}

// Method 3: Check in code
if ($user->canApprove($request)) {
    // Show approval form
}
```

**In Blade:**
```blade
@can('approve', $request)
    <button class="btn-approve">Approve</button>
@endcan

@cannot('delete', $request)
    <p class="text-danger">You cannot delete this request</p>
@endcannot
```

---

### 7. Getting Approval Timeline

**Controller:**
```php
$request = StationaryRequest::with('approvals.approver')->find($id);

$timeline = $request->approvals()
    ->with('approver')
    ->orderBy('created_at', 'asc')
    ->get()
    ->map(function($approval) {
        return [
            'role' => ucfirst(str_replace('_', ' ', $approval->role)),
            'approver' => $approval->approver->name,
            'status' => $approval->status,
            'date' => $approval->created_at->format('M d, Y H:i'),
            'remarks' => $approval->remarks,
        ];
    });
```

**Blade:**
```blade
<div class="timeline">
    @foreach($timeline as $approval)
        <div class="timeline-item">
            <div class="timeline-marker 
                badge-{{ $approval['status'] === 'approved' ? 'success' : 'danger' }}">
                ✓
            </div>
            <div class="timeline-content">
                <h5>{{ $approval['role'] }}</h5>
                <p>{{ $approval['approver'] }} - {{ $approval['status'] }}</p>
                @if($approval['remarks'])
                    <p class="remarks">{{ $approval['remarks'] }}</p>
                @endif
                <small>{{ $approval['date'] }}</small>
            </div>
        </div>
    @endforeach
</div>
```

---

### 8. Provider Marking Request Supplied

**Route:**
```php
Route::post('/requests/{request}/supplied', 
    [ApprovalController::class, 'markSupplied'])
    ->middleware('role:provider');
```

**Controller:**
```php
public function markSupplied(StationaryRequest $request)
{
    $user = Auth::user();
    
    // Verify authorization
    if (!$user->isProvider()) {
        abort(403);
    }
    
    if ($request->status !== 'sent_to_provider') {
        return redirect()->back()
            ->with('error', 'Request not ready for supply');
    }
    
    DB::transaction(function () use ($request) {
        // Reduce product stock
        foreach ($request->items as $item) {
            $item->product->decrement('stock_quantity', $item->quantity);
        }
        
        // Mark as completed
        $request->update(['status' => 'completed']);
    });
    
    return redirect()->route('dashboard')
        ->with('success', 'Request marked as supplied');
}
```

**Blade:**
```blade
@if($request->status === 'sent_to_provider' && auth()->user()->isProvider())
    <form action="{{ route('requests.supplied', $request) }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-success">
            Mark as Supplied
        </button>
    </form>
@endif
```

---

### 9. Getting Role-Based Statistics

**Controller:**
```php
$user = Auth::user();
$service = new ApprovalWorkflowService();

$stats = $service->getApprovalStats($user);

// For Teachers:
$stats = [
    'my_requests' => 5,
    'pending' => 2,
    'approved' => 2,
    'rejected' => 1,
];

// For HODs:
$stats = [
    'pending_approvals' => 3,
    'total_in_dept' => 15,
];

// For Admins:
$stats = [
    'total_requests' => 50,
    'pending' => 5,
    'completed' => 40,
];
```

---

### 10. Listing Pending Approvals for Dashboard

**Controller:**
```php
public function index()
{
    $user = Auth::user();
    $service = new ApprovalWorkflowService();
    
    // Get pending approvals for this user's role
    $pending = $service->getPendingApprovalsForUser($user);
    
    return view('dashboard', compact('pending'));
}
```

**Blade:**
```blade
<div class="pending-approvals">
    <h3>Pending Approvals ({{ $pending->count() }})</h3>
    
    @forelse($pending as $request)
        <div class="approval-card">
            <h5>Request #{{ $request->id }}</h5>
            <p>
                <strong>From:</strong> {{ $request->requestedBy->name }}<br>
                <strong>Department:</strong> {{ $request->department->name }}<br>
                <strong>Amount:</strong> ₹{{ $request->total_amount }}
            </p>
            <a href="{{ route('approvals.show', $request) }}" 
               class="btn btn-primary">
                Review & Approve
            </a>
        </div>
    @empty
        <p class="text-muted">No pending approvals</p>
    @endforelse
</div>
```

---

## 📊 Status Values Reference

```php
// Use in queries
$pending = StationaryRequest::where('status', 'pending')->get();
$hod_approved = StationaryRequest::where('status', 'hod_approved')->get();
$completed = StationaryRequest::where('status', 'completed')->get();
$rejected = StationaryRequest::where('status', 'rejected')->get();

// Check status on model
if ($request->isPending()) { /* ... */ }
if ($request->isHodApproved()) { /* ... */ }
if ($request->isPrincipalApproved()) { /* ... */ }
if ($request->isTrustApproved()) { /* ... */ }
if ($request->isSentToProvider()) { /* ... */ }
if ($request->isCompleted()) { /* ... */ }
if ($request->isRejected()) { /* ... */ }
```

---

## 🔐 Role Constants Reference

```php
// Role values
'admin'
'teacher'
'hod'
'principal'
'trust_head'
'provider'

// Check on model
if ($user->isAdmin()) { /* ... */ }
if ($user->isTeacher()) { /* ... */ }
if ($user->isHOD()) { /* ... */ }
if ($user->isPrincipal()) { /* ... */ }
if ($user->isTrustHead()) { /* ... */ }
if ($user->isProvider()) { /* ... */ }
```

---

## 🛠️ Helper Functions

### Get Next Approval Level
```php
$nextLevel = match($user->role) {
    'hod' => 'hod_approved',
    'principal' => 'principal_approved',
    'trust_head' => 'trust_approved',
    'admin' => 'sent_to_provider',
    default => $request->status,
};
```

### Format Status for Display
```php
$displayStatus = match($request->status) {
    'pending' => 'Pending HOD Approval',
    'hod_approved' => 'Approved by HOD - Awaiting Principal',
    'principal_approved' => 'Approved by Principal - Awaiting Trust Head',
    'trust_approved' => 'Approved by Trust Head - Ready for Supply',
    'sent_to_provider' => 'Sent to Provider',
    'completed' => 'Completed Successfully',
    'rejected' => 'Request Rejected',
    default => ucfirst(str_replace('_', ' ', $request->status)),
};
```

### Get Status Badge Color
```php
$color = match($request->status) {
    'pending' => 'warning',
    'hod_approved' => 'info',
    'principal_approved' => 'primary',
    'trust_approved' => 'success',
    'sent_to_provider' => 'secondary',
    'completed' => 'success',
    'rejected' => 'danger',
    default => 'secondary',
};
```

---

## 📝 Database Queries Reference

### Get Request with All Data
```php
$request = StationaryRequest::with([
    'department',
    'requestedBy',
    'items.product',
    'approvals.approver'
])->find($id);
```

### Get Pending Requests for Department
```php
$requests = StationaryRequest::where('department_id', $deptId)
    ->where('status', 'pending')
    ->with(['requestedBy', 'items'])
    ->get();
```

### Get All Approvals for Request
```php
$approvals = Approval::where('request_id', $requestId)
    ->with('approver')
    ->orderBy('created_at')
    ->get();
```

### Check if Approved by Specific Role
```php
$approved = Approval::where('request_id', $requestId)
    ->where('role', 'hod')
    ->where('status', 'approved')
    ->exists();
```

---

## 🚦 Common Validations

### Authorization Check
```php
// In controller
if ($request->requested_by !== auth()->id() && !auth()->user()->isAdmin()) {
    abort(403, 'Unauthorized');
}

// In policy
public function update(User $user, StationaryRequest $request): bool
{
    return $request->status === 'pending' && 
           $request->requested_by === $user->id;
}
```

### Stock Validation
```php
// Check available stock
foreach ($items as $item) {
    $product = Product::find($item['product_id']);
    if ($product->stock_quantity < $item['quantity']) {
        throw new \Exception("Insufficient stock for {$product->name}");
    }
}
```

### Workflow Validation
```php
// Can only approve if status is correct
if ($user->isHOD() && $request->status !== 'pending') {
    abort(403, 'Request must be pending for HOD approval');
}
```

---

## 💡 Tips & Best Practices

1. **Always eager load relationships** to prevent N+1 queries
2. **Use transactions** for multi-step operations
3. **Validate authorization** before showing forms
4. **Check status** before allowing operations
5. **Log approvals** for audit trail
6. **Provide clear error messages** to users
7. **Paginate large lists** (15-25 items per page)
8. **Cache dashboard stats** for performance
9. **Use routes with middleware** for role protection
10. **Test approval workflow** end-to-end

---

## 🐛 Debugging

### Check Request Status
```php
dd($request->status, $request->approvals->pluck('role', 'status'));
```

### Check User Role
```php
dd(auth()->user()->role, auth()->user()->isHOD());
```

### Check Stock
```php
dd($product->stock_quantity, $item->quantity);
```

### Check Approvals
```php
foreach ($request->approvals as $approval) {
    echo "{$approval->approver->name} ({$approval->role}): {$approval->status}\n";
}
```

---

This quick start guide covers 90% of common workflow usage. For more details, refer to APPROVAL_WORKFLOW_GUIDE.md
