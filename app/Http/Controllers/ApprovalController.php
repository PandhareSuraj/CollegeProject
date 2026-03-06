<?php

namespace App\Http\Controllers;

use App\Events\RequestApproved;
use App\Events\RequestRejected;
use App\Events\RequestSupplied;
use App\Models\StationaryRequest;
use App\Models\Approval;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ApprovalController extends Controller
{
    /**
     * Show the approval form for a specific request
     */
    public function show(StationaryRequest $stationaryRequest)
    {
        $user = Auth::user();

        // Load relationships for display
            $stationaryRequest->load([
                'department',
                'items.product',
                'approvals' => function ($q) {
                    $q->with('approver')->orderBy('created_at', 'desc');
                }
            ]);

    // Determine if the current user can approve - used to enable/disable the action in the view
    $canApprove = $this->canUserApprove($user, $stationaryRequest);

    // Provide diagnostics explaining why approval may be disallowed (for UI troubleshooting)
    $diagnostics = [
        'is_request_owner' => ($stationaryRequest->requested_by == $user->id || $stationaryRequest->requested_by == $user->email),
        'is_status_allowed' => !in_array($stationaryRequest->status, ['rejected', 'completed']),
        'hod_status_ok' => ($user->isHOD() && $stationaryRequest->status === 'pending' && $stationaryRequest->department_id === $user->department_id),
        'already_hod_approved' => Approval::where('request_id', $stationaryRequest->id)->where('role', 'hod')->exists(),
    ];

    // Add raw identifiers to help debug mismatches
    $diagnostics['user_id'] = $user->id ?? null;
    $diagnostics['user_role'] = $user->role ?? null;
    $diagnostics['user_department_id'] = $user->department_id ?? null;
    $diagnostics['request_department_id'] = $stationaryRequest->department_id ?? null;
    $diagnostics['request_status'] = $stationaryRequest->status ?? null;
    $diagnostics['requested_by'] = $stationaryRequest->requested_by ?? null;

        // Get approval history
        $approvalHistory = $stationaryRequest->approvals()
            ->orderBy('created_at')
            ->get();

        // Get workflow info
        $workflowInfo = $this->getWorkflowInfo($stationaryRequest);

    return view('approvals.show', compact('stationaryRequest', 'user', 'approvalHistory', 'workflowInfo', 'canApprove', 'diagnostics'));
    }

    /**
     * Store the approval decision
     */
    public function store(Request $request, StationaryRequest $stationaryRequest)
    {
        $user = Auth::user();

        // Verify authorization
        if (!$this->canUserApprove($user, $stationaryRequest)) {
            return redirect()->route('dashboard')
                ->with('error', 'You are not authorized to approve this request.');
        }

        // Validate input
        $validated = $request->validate([
            'status' => 'required|in:approved,rejected',
            'remarks' => 'nullable|string|max:500',
        ]);

        try {
            return DB::transaction(function () use ($user, $stationaryRequest, $validated) {
                // Check if this role has already approved
                $existingApproval = Approval::where('request_id', $stationaryRequest->id)
                    ->where('role', $user->role)
                    ->first();

                if ($existingApproval) {
                    return redirect()->route('dashboard')
                        ->with('error', 'You have already approved this request at the ' . str_replace('_', ' ', $user->role) . ' level.');
                }

                // Create approval record
                $approval = Approval::create([
                    'request_id' => $stationaryRequest->id,
                    'approved_by' => $user->id,
                    'role' => $user->role,
                    'status' => $validated['status'],
                    'remarks' => $validated['remarks'] ?? null,
                ]);

                // Handle rejection - stop workflow
                if ($validated['status'] === 'rejected') {
                    $stationaryRequest->update(['status' => 'rejected']);
                    
                    // Log audit
                    $this->logAudit($stationaryRequest, $user, 'Request rejected at ' . str_replace('_', ' ', $user->role) . ' level');

                    // Dispatch rejection event
                    Event::dispatch(new RequestRejected($stationaryRequest, $approval, $validated['remarks'] ?? ''));

                    return redirect()->route('dashboard')
                        ->with('success', 'Request rejected successfully. Requestor has been notified.');
                }

                // Handle approval - advance to next stage
                $nextStatus = $this->getNextApprovalStatus($user, $stationaryRequest);
                $stationaryRequest->update(['status' => $nextStatus]);

                // If Trust Head just approved, automatically send to provider
                if ($user->isTrustHead() && $nextStatus === 'trust_approved') {
                    // Mark as sent_to_provider
                    $stationaryRequest->update(['status' => 'sent_to_provider']);
                    $this->logAudit($stationaryRequest, $user, 'Request automatically sent to provider after all approvals');

                    // Create an Order record and link it to the request (choose a vendor heuristically)
                    try {
                        if (class_exists(\App\Models\Order::class)) {
                            $vendor = \App\Models\Vendor::query()->orderBy('id')->first();

                            $order = \App\Models\Order::create([
                                'order_number' => 'ORD-' . strtoupper(uniqid()),
                                'vendor_id' => $vendor->id ?? null,
                                'status' => 'pending',
                                'meta' => [
                                    'request_id' => $stationaryRequest->id,
                                    'created_by' => $user->id,
                                ],
                            ]);

                            // Copy request items into order items
                            $total = 0;
                            foreach ($stationaryRequest->items as $ri) {
                                $qty = $ri->quantity;
                                $price = $ri->price;
                                $subtotal = $qty * $price;
                                $total += $subtotal;

                                \App\Models\OrderItem::create([
                                    'order_id' => $order->id,
                                    'product_id' => $ri->product_id,
                                    'quantity' => $qty,
                                    'price' => $price,
                                    'subtotal' => $subtotal,
                                ]);
                            }

                            // Update order meta and status
                            $order->meta = array_merge($order->meta ?? [], ['total_amount' => $total]);
                            $order->save();

                            // Link order to request if column exists
                            if (Schema::hasColumn('requests', 'order_id')) {
                                $stationaryRequest->order_id = $order->id;
                                $stationaryRequest->save();
                            }
                        }
                    } catch (\Throwable $e) {
                        // Log but do not fail the approval flow
                        \Log::warning('Failed to create order for request '.$stationaryRequest->id.': '.$e->getMessage());
                    }

                    // Dispatch event to notify provider
                    $providers = \App\Models\Provider::all();
                    foreach ($providers as $provider) {
                        Mail::to($provider->email)
                            ->send(new \App\Mail\RequestApprovedNotification($stationaryRequest, $approval));
                    }
                }

                // Log audit
                $this->logAudit($stationaryRequest, $user, 'Request approved at ' . str_replace('_', ' ', $user->role) . ' level');

                // Dispatch approval event
                Event::dispatch(new RequestApproved($stationaryRequest, $approval));

                return redirect()->route('dashboard')
                    ->with('success', 'Request approved successfully. Moving to next approval level.');
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process approval: ' . $e->getMessage());
        }
    }

    /**
     * Show approval form for a specific request
     */
    public function edit(StationaryRequest $stationaryRequest)
    {
        $user = Auth::user();

        if (!$this->canUserApprove($user, $stationaryRequest)) {
            abort(403, 'You are not authorized to review this request.');
        }

            $stationaryRequest->load(['department', 'items.product', 'approvals' => function ($q) {
                $q->with('approver');
            }]);
        
        return view('approvals.edit', compact('stationaryRequest', 'user'));
    }

    /**
     * Mark request as supplied by provider
     */
    public function markSupplied(StationaryRequest $stationaryRequest)
    {
        $user = Auth::user();

        // Only provider can mark as supplied
        if (!$user->isProvider()) {
            abort(403, 'Only providers can mark requests as supplied.');
        }

        // Only mark supplied requests that are ready
        if ($stationaryRequest->status !== 'sent_to_provider') {
            return redirect()->back()
                ->with('error', 'This request is not ready for supply.');
        }

        try {
            return DB::transaction(function () use ($user, $stationaryRequest) {
                // Reduce product stock for all items
                foreach ($stationaryRequest->items as $item) {
                    $product = $item->product;
                    
                    if ($product->stock_quantity < $item->quantity) {
                        throw new \Exception(
                            "Insufficient stock for {$product->name}. Available: {$product->stock_quantity}, Required: {$item->quantity}"
                        );
                    }

                    $product->decrement('stock_quantity', $item->quantity);
                }

                // Mark request as completed
                $stationaryRequest->update(['status' => 'completed']);

                // If an order is linked, mark it as completed as well
                try {
                    if (method_exists($stationaryRequest, 'order') && $stationaryRequest->order) {
                        $order = $stationaryRequest->order;
                        $order->status = 'completed';
                        $order->save();
                    }
                } catch (\Throwable $e) {
                    \Log::warning('Failed to update order status for request '.$stationaryRequest->id.': '.$e->getMessage());
                }

                // Log audit
                $this->logAudit($stationaryRequest, $user, 'Request marked as supplied and completed by provider');

                // Dispatch request supplied event
                Event::dispatch(new RequestSupplied($stationaryRequest, $user));

                return redirect()->route('dashboard')
                    ->with('success', 'Request marked as supplied and completed successfully.');
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to mark request as supplied: ' . $e->getMessage());
        }
    }

    /**
     * Get pending approvals for current user's role
     */
    public function getPendingApprovals($user)
    {
        $query = StationaryRequest::query();

        switch ($user->role) {
            case 'hod':
                // HOD sees pending requests from their department
                $query->where('department_id', $user->department_id)
                      ->where('status', 'pending')
                      ->whereNotIn('requested_by', [$user->id]);
                break;

            case 'principal':
                // Principal sees HOD-approved requests
                $query->where('status', 'hod_approved');
                break;

            case 'trust_head':
                // Trust Head sees Principal-approved requests
                $query->where('status', 'principal_approved');
                break;

            case 'provider':
                // Provider sees requests ready for supply
                $query->where('status', 'sent_to_provider');
                break;

            case 'admin':
                // Admin sees trust-approved requests and can handle any rejection
                $query->where('status', 'trust_approved');
                break;

            default:
                $query->whereRaw('1=0'); // No pending approvals for other roles
        }

        return $query->with(['department', 'items'])
                    ->latest()
                    ->get();
    }

    /**
     * Check if user can approve the request
     */
    private function canUserApprove($user, StationaryRequest $stationaryRequest): bool
    {
        // Can't approve own request (requested_by may store id or email)
        if ($stationaryRequest->requested_by == $user->id || $stationaryRequest->requested_by == $user->email) {
            return false;
        }

        // Can't approve already rejected or completed requests
        if (in_array($stationaryRequest->status, ['rejected', 'completed'])) {
            return false;
        }

        // Verify role-specific conditions
        switch ($user->role) {
            case 'hod':
                // HOD approves pending requests from their department
                if ($stationaryRequest->status === 'pending' && 
                    (empty($user->department_id) || $stationaryRequest->department_id === $user->department_id)) {
                    // Check they haven't already approved
                    return !Approval::where('request_id', $stationaryRequest->id)
                        ->where('role', 'hod')
                        ->exists();
                }
                return false;

            case 'principal':
                // Principal approves HOD-approved requests
                if ($stationaryRequest->status === 'hod_approved') {
                    return !Approval::where('request_id', $stationaryRequest->id)
                        ->where('role', 'principal')
                        ->exists();
                }
                return false;

            case 'trust_head':
                // Trust Head approves Principal-approved requests
                if ($stationaryRequest->status === 'principal_approved') {
                    return !Approval::where('request_id', $stationaryRequest->id)
                        ->where('role', 'trust_head')
                        ->exists();
                }
                return false;

            case 'admin':
                // Admin can approve at any stage except final and rejected
                if (!in_array($stationaryRequest->status, ['trust_approved', 'sent_to_provider', 'completed', 'rejected'])) {
                    return true;
                }
                return false;

            default:
                return false;
        }
    }

    /**
     * Determine the next approval status based on current role
     */
    private function getNextApprovalStatus($user, StationaryRequest $stationaryRequest): string
    {
        switch ($user->role) {
            case 'hod':
                return 'hod_approved';
            case 'principal':
                return 'principal_approved';
            case 'trust_head':
                return 'trust_approved';
            case 'admin':
                return 'sent_to_provider';
            default:
                return $stationaryRequest->status;
        }
    }

    /**
     * Get detailed workflow information
     */
    private function getWorkflowInfo(StationaryRequest $stationaryRequest): array
    {
        // Be defensive: the approvals relation may not be available or may be null in some contexts.
        try {
            if ($stationaryRequest->relationLoaded('approvals') && is_iterable($stationaryRequest->approvals)) {
                $approvalsCollection = collect($stationaryRequest->approvals);
            } else {
                // If the relation method exists, attempt to load it; otherwise fall back to empty collection.
                if (method_exists($stationaryRequest, 'approvals')) {
                    $relation = $stationaryRequest->approvals();
                    if ($relation !== null) {
                        $approvalsCollection = $relation->with('approver')->get();
                    } else {
                        $approvalsCollection = collect();
                    }
                } else {
                    $approvalsCollection = collect();
                }
            }

            $approvals = $approvalsCollection->groupBy('role');
        } catch (\Throwable $e) {
            // On any unexpected issue, return an empty approvals set so the page can render.
            \Log::warning('Failed to load approvals for workflow info: ' . $e->getMessage(), ['request_id' => $stationaryRequest->id]);
            $approvals = collect();
        }

        return [
            'current_status' => $stationaryRequest->status,
            'approvals' => [
                'hod' => optional($approvals->get('hod'))->first(),
                'principal' => optional($approvals->get('principal'))->first(),
                'trust_head' => optional($approvals->get('trust_head'))->first(),
                'admin' => optional($approvals->get('admin'))->first(),
            ],
            'workflow_steps' => [
                'pending' => 'Awaiting HOD Approval',
                'hod_approved' => 'Awaiting Principal Approval',
                'principal_approved' => 'Awaiting Trust Head Approval',
                'trust_approved' => 'Ready for Supply',
                'sent_to_provider' => 'Supplied by Provider',
                'completed' => 'Completed',
                'rejected' => 'Rejected',
            ],
        ];
    }

    /**
     * Log approval audit trail
     */
    private function logAudit(StationaryRequest $stationaryRequest, $user, string $action): void
    {
        // Can be extended to store in audit table
        \Log::info('Request Audit', [
            'request_id' => $stationaryRequest->id,
            'user_id' => $user->id,
            'user_role' => $user->role,
            'action' => $action,
            'timestamp' => now(),
        ]);
    }

    /**
     * Get statistics for approval dashboard
     */
    public function getApprovalStats($user)
    {
        $pending = $this->getPendingApprovals($user)->count();

        return [
            'pending_approvals' => $pending,
            'recent_approvals' => Approval::where('approved_by', $user->id)
                ->with('request')
                ->latest()
                ->limit(5)
                ->get(),
        ];
    }
}
