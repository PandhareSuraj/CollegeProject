<?php

namespace App\Http\Controllers;

use App\Models\StationaryRequest;
use App\Models\RequestItem;
use App\Models\Product;
use App\Models\Approval;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestController extends Controller
{
    /**
     * Display a listing of requests with role-based filtering
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Base query with eager loading for performance
        $query = StationaryRequest::with([
            'department',
            'items.product',
            'approvals.approver'
        ]);

        // Filter by status if provided
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Role-based filtering
        if ($user->isTeacher()) {
            // Teachers see only their own requests
            $query->where('requested_by', $user->id);
        } elseif ($user->isHOD()) {
            // HODs see only their department's requests
            $query->where('department_id', $user->department_id)
                  ->orWhere('requested_by', $user->id);
        }
        // Admin and other roles see all requests

        try {
            $requests = $query->latest()->paginate(15);
        } catch (\Throwable $e) {
            // Defensive: if eager-loading a nested/dynamic relation fails (some runtime setups
            // may return null relation definitions), log the error and fall back to a safer
            // eager-load that omits the nested approver relation so the view can still render.
            \Log::error('RequestController@index - eager load failed', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            $requests = StationaryRequest::with(['department', 'items.product', 'approvals'])
                ->latest()
                ->paginate(15);
        }

        // Count requests by status for filter display
        $statusCounts = StationaryRequest::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return view('requests.index', compact('requests', 'statusCounts'));
    }

    /**
     * Show the form for creating a new request
     */
    public function create()
    {
        $user = Auth::user();
        
        // Get all departments for selection
        $departments = Department::all();

        $products = Product::where('stock_quantity', '>', 0)
                          ->orderBy('name')
                          ->get();

        return view('requests.create', compact('products', 'departments'));
    }

    /**
     * Store a newly created request in storage with transaction support
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Validate input
        $validated = $request->validate([
            'items' => 'required|array|min:1|max:50',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000',
            'department_id' => 'required|exists:departments,id',
        ], [
            'items.required' => 'At least one item is required.',
            'items.min' => 'At least one item is required.',
            'items.max' => 'Maximum 50 items per request.',
            'items.*.product_id.required' => 'Product is required for each item.',
            'items.*.quantity.min' => 'Quantity must be at least 1.',
            'department_id.required' => 'Department is required.',
        ]);

        try {
            return DB::transaction(function () use ($user, $validated) {
                // Create the request
                $stationaryRequest = StationaryRequest::create([
                    'department_id' => $validated['department_id'],
                    'requested_by' => $user->id,
                    'status' => 'pending',
                    'total_amount' => 0,
                ]);

                $totalAmount = 0;

                // Create request items
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    
                    // Validate available quantity
                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \Exception(
                            "Not enough stock for {$product->name}. Available: {$product->stock_quantity}, Requested: {$item['quantity']}"
                        );
                    }

                    $subtotal = $product->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    RequestItem::create([
                        'request_id' => $stationaryRequest->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Update total amount
                // Ensure the request remains owned by the editor and stays pending
                $stationaryRequest->update([
                    'total_amount' => $totalAmount,
                    'status' => 'pending',
                    'requested_by' => $user->id,
                ]);

                return redirect()->route('requests.show', $stationaryRequest->id)
                    ->with('success', 'Request created successfully. It is now pending HOD approval.');
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified request with full approval workflow
     */
    public function show(StationaryRequest $stationaryRequest)
    {
        $stationaryRequest->load([
            'department',
            'items.product',
            'approvals' => function ($q) {
                $q->with('approver')->orderBy('created_at', 'desc');
            }
        ]);

        $user = Auth::user();
        
        // Determine if current user can approve
        $canApprove = $this->canUserApprove($user, $stationaryRequest);
        
        // Get next approval workflow step
        $workflowStatus = $this->getWorkflowStatus($stationaryRequest);

        return view('requests.show', compact('stationaryRequest', 'canApprove', 'workflowStatus'));
    }

    /**
     * Show the form for editing the specified request
     */
    public function edit(StationaryRequest $stationaryRequest)
    {
        $user = Auth::user();
        
        // Only allow editing pending requests by requestor or admin
        if ($stationaryRequest->status !== 'pending') {
            return redirect()->route('requests.show', $stationaryRequest)
                ->with('error', 'Only pending requests can be edited.');
        }

        if ((int) $stationaryRequest->requested_by !== (int) $user->id && !$user->isAdmin()) {
            abort(403, 'You are not authorized to edit this request.');
        }

        $products = Product::orderBy('name')->get();
        
        return view('requests.edit', compact('stationaryRequest', 'products'));
    }

    /**
     * Update the specified request in storage
     */
    public function update(Request $request, StationaryRequest $stationaryRequest)
    {
        $user = Auth::user();

        // Validate authorization
        if ($stationaryRequest->status !== 'pending') {
            return redirect()->route('requests.show', $stationaryRequest)
                ->with('error', 'Only pending requests can be edited.');
        }

        if ($stationaryRequest->requested_by !== $user->id && !$user->isAdmin()) {
            abort(403, 'You are not authorized to edit this request.');
        }

        // Validate input
        $validated = $request->validate([
            'items' => 'required|array|min:1|max:50',
            'items.*.product_id' => 'required|integer|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:1000',
        ]);

        try {
            return DB::transaction(function () use ($stationaryRequest, $validated) {
                // Delete old items
                $stationaryRequest->items()->delete();

                $totalAmount = 0;

                // Create new items
                foreach ($validated['items'] as $item) {
                    $product = Product::find($item['product_id']);
                    $subtotal = $product->price * $item['quantity'];
                    $totalAmount += $subtotal;

                    RequestItem::create([
                        'request_id' => $stationaryRequest->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ]);
                }

                // Update total amount
                $stationaryRequest->update(['total_amount' => $totalAmount]);

                return redirect()->route('requests.show', $stationaryRequest->id)
                    ->with('success', 'Request updated successfully.');
            });
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update request: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified request from storage
     */
    public function destroy(StationaryRequest $stationaryRequest)
    {
        $user = Auth::user();

        // Only allow deleting pending requests by requestor or admin
        if ($stationaryRequest->status !== 'pending') {
            return redirect()->route('requests.show', $stationaryRequest)
                ->with('error', 'Only pending requests can be deleted.');
        }

        if ((int) $stationaryRequest->requested_by !== (int) $user->id && !$user->isAdmin()) {
            abort(403, 'You are not authorized to delete this request.');
        }

        try {
            DB::transaction(function () use ($stationaryRequest) {
                $stationaryRequest->items()->delete();
                $stationaryRequest->approvals()->delete();
                $stationaryRequest->delete();
            });

            return redirect()->route('requests.index')
                ->with('success', 'Request deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete request: ' . $e->getMessage());
        }
    }

    /**
     * Determine if user can approve this request
     */
    private function canUserApprove($user, StationaryRequest $stationaryRequest): bool
    {
        // Can't approve own request
        if ($stationaryRequest->requested_by === $user->id) {
            return false;
        }

        // Check workflow status
        $status = $stationaryRequest->status;

        switch (true) {
            // HOD approves pending requests from their department
            case $user->isHOD() && $status === 'pending' && $stationaryRequest->department_id === $user->department_id:
                // Check if HOD has already approved
                return !Approval::where('request_id', $stationaryRequest->id)
                    ->where('role', 'hod')
                    ->exists();

            // Principal approves HOD-approved requests
            case $user->isPrincipal() && $status === 'hod_approved':
                return !Approval::where('request_id', $stationaryRequest->id)
                    ->where('role', 'principal')
                    ->exists();

            // Trust Head approves Principal-approved requests
            case $user->isTrustHead() && $status === 'principal_approved':
                return !Approval::where('request_id', $stationaryRequest->id)
                    ->where('role', 'trust_head')
                    ->exists();

            // Admin can approve at any stage
            case $user->isAdmin() && !in_array($status, ['sent_to_provider', 'completed', 'rejected']):
                return true;

            default:
                return false;
        }
    }

    /**
     * Get detailed workflow status information
     */
    private function getWorkflowStatus(StationaryRequest $stationaryRequest): array
    {
        $approvals = $stationaryRequest->approvals()
            ->orderBy('created_at')
            ->get()
            ->groupBy('role');

        return [
            'current_status' => $stationaryRequest->status,
            'hod_approval' => optional($approvals->get('hod'))->first() ?? null,
            'principal_approval' => optional($approvals->get('principal'))->first() ?? null,
            'trust_approval' => optional($approvals->get('trust_head'))->first() ?? null,
            'admin_approval' => optional($approvals->get('admin'))->first() ?? null,
            'pending_approvals' => [
                'hod' => !$stationaryRequest->isPending() ? false : true,
                'principal' => !$stationaryRequest->isHodApproved() ? false : true,
                'trust_head' => !$stationaryRequest->isPrincipalApproved() ? false : true,
            ],
        ];
    }

    /**
     * Get user-specific dashboard statistics
     */
    public function getDashboardStats($user)
    {
        $query = StationaryRequest::query();

        if ($user->isTeacher()) {
            // Teacher stats
            return [
                'total' => $query->where('requested_by', $user->id)->count(),
                'pending' => $query->where('requested_by', $user->id)->where('status', 'pending')->count(),
                'approved' => $query->where('requested_by', $user->id)->where('status', 'completed')->count(),
                'rejected' => $query->where('requested_by', $user->id)->where('status', 'rejected')->count(),
            ];
        } elseif ($user->isHOD()) {
            // HOD stats
            return [
                'pending_approval' => $query->where('department_id', $user->department_id)->where('status', 'pending')->count(),
                'total' => $query->where('department_id', $user->department_id)->count(),
                'approved' => $query->where('department_id', $user->department_id)->where('status', 'completed')->count(),
                'rejected' => $query->where('department_id', $user->department_id)->where('status', 'rejected')->count(),
            ];
        } elseif ($user->isPrincipal()) {
            // Principal stats
            return [
                'pending_approval' => $query->where('status', 'hod_approved')->count(),
                'total' => $query->count(),
                'completed' => $query->where('status', 'completed')->count(),
            ];
        } elseif ($user->isTrustHead()) {
            // Trust Head stats
            return [
                'pending_approval' => $query->where('status', 'principal_approved')->count(),
                'total' => $query->count(),
                'completed' => $query->where('status', 'completed')->count(),
            ];
        } elseif ($user->isProvider()) {
            // Provider stats
            return [
                'ready_to_supply' => $query->where('status', 'trust_approved')->count(),
                'supplied' => $query->where('status', 'sent_to_provider')->count(),
                'completed' => $query->where('status', 'completed')->count(),
            ];
        } else {
            // Admin stats
            return [
                'total' => $query->count(),
                'pending' => $query->where('status', 'pending')->count(),
                'approved' => $query->where('status', 'trust_approved')->count(),
                'completed' => $query->where('status', 'completed')->count(),
                'rejected' => $query->where('status', 'rejected')->count(),
                'total_amount' => $query->sum('total_amount'),
            ];
        }
    }

    /**
     * Get approval timeline details
     */
    public function getApprovalTimeline(StationaryRequest $stationaryRequest): array
    {
        $approvals = $stationaryRequest->approvals()
            ->with('approver')
            ->orderBy('created_at', 'asc')
            ->get();

        return $approvals->map(function (Approval $approval) {
            return [
                'role' => ucfirst(str_replace('_', ' ', $approval->role)),
                'approver' => $approval->approver?->name ?? 'N/A',
                'status' => strtoupper($approval->status),
                'date' => $approval->created_at->format('M d, Y H:i'),
                'remarks' => $approval->remarks,
            ];
        })->toArray();
    }
}
