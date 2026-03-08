<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\StationaryRequest;
use App\Models\Approval;
use App\Models\Product;
use App\Models\RequestItem;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalRequests     = StationaryRequest::count();
        $pendingRequests   = StationaryRequest::where('status', 'pending')->count();
        $approvedRequests  = StationaryRequest::whereIn('status', [
                                'hod_approved', 'principal_approved', 'trust_approved'
                             ])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();
        $rejectedRequests  = StationaryRequest::where('status', 'rejected')->count();
        $totalAmount       = StationaryRequest::sum('total_amount') ?? 0;

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', [
                    'hod_approved', 'principal_approved', 'trust_approved',
                    'sent_to_provider', 'completed'
                ]);
            });
        })->select('id', 'name')->paginate(15);

        return view('dashboards.admin', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'totalAmount',
            'approvedProducts'
        ));
    }

    public function teacher()
    {
        $user = Auth::user();

        $requests = StationaryRequest::where('requested_by', $user->id)
            ->select('id', 'department_id', 'requested_by', 'status', 'total_amount', 'created_at')
            ->latest()
            ->paginate(15);

        $totalRequests     = StationaryRequest::where('requested_by', $user->id)->count();
        $pendingRequests   = StationaryRequest::where('requested_by', $user->id)
                                ->where('status', 'pending')->count();
        $approvedRequests  = StationaryRequest::where('requested_by', $user->id)
                                ->whereIn('status', [
                                    'hod_approved', 'principal_approved', 'trust_approved'
                                ])->count();
        $completedRequests = StationaryRequest::where('requested_by', $user->id)
                                ->where('status', 'completed')->count();
        $rejectedRequests  = StationaryRequest::where('requested_by', $user->id)
                                ->where('status', 'rejected')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', [
                    'hod_approved', 'principal_approved', 'trust_approved',
                    'sent_to_provider', 'completed'
                ]);
            });
        })->select('id', 'name')->paginate(15);

        return view('dashboards.teacher', compact(
            'requests',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'approvedProducts'
        ));
    }

    public function hod()
    {
        $user = Auth::user();

        $requests = StationaryRequest::where('status', 'pending')
            ->select('id', 'department_id', 'requested_by', 'status', 'total_amount', 'created_at')
            ->latest()
            ->paginate(15);

        $totalRequests     = StationaryRequest::count();
        $pendingRequests   = StationaryRequest::where('status', 'pending')->count();
        $pendingApprovals  = $pendingRequests; // alias — blade may use either name
        $approvedRequests  = StationaryRequest::whereIn('status', [
                                'hod_approved', 'principal_approved', 'trust_approved'
                             ])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();
        $rejectedRequests  = StationaryRequest::where('status', 'rejected')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', [
                    'hod_approved', 'principal_approved', 'trust_approved',
                    'sent_to_provider', 'completed'
                ]);
            });
        })->select('id', 'name')->paginate(15);

        return view('dashboards.hod', compact(
            'requests',
            'totalRequests',
            'pendingRequests',
            'pendingApprovals',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'approvedProducts'
        ));
    }

    public function principal()
    {
        $requests = StationaryRequest::where('status', 'hod_approved')
            ->select('id', 'department_id', 'requested_by', 'status', 'total_amount', 'created_at')
            ->latest()
            ->paginate(15);

        $totalRequests     = StationaryRequest::count();
        $pendingRequests   = StationaryRequest::where('status', 'hod_approved')->count();
        $pendingApprovals  = $pendingRequests; // alias
        $approvedRequests  = StationaryRequest::whereIn('status', [
                                'principal_approved', 'trust_approved'
                             ])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();
        $rejectedRequests  = StationaryRequest::where('status', 'rejected')->count(); // ← was missing

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', [
                    'hod_approved', 'principal_approved', 'trust_approved',
                    'sent_to_provider', 'completed'
                ]);
            });
        })->select('id', 'name')->paginate(15);

        return view('dashboards.principal', compact(
            'requests',
            'totalRequests',
            'pendingRequests',
            'pendingApprovals',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',    // ← the fix
            'approvedProducts'
        ));
    }

    public function trustHead()
    {
        $requests = StationaryRequest::where('status', 'principal_approved')
            ->select('id', 'department_id', 'requested_by', 'status', 'total_amount', 'created_at')
            ->latest()
            ->paginate(15);

        $totalRequests     = StationaryRequest::count();
        $pendingRequests   = StationaryRequest::where('status', 'principal_approved')->count();
        $pendingApprovals  = $pendingRequests; // alias
        $approvedRequests  = StationaryRequest::where('status', 'trust_approved')->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();
        $rejectedRequests  = StationaryRequest::where('status', 'rejected')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', [
                    'hod_approved', 'principal_approved', 'trust_approved',
                    'sent_to_provider', 'completed'
                ]);
            });
        })->select('id', 'name')->paginate(15);

        return view('dashboards.trust-head', compact(
            'requests',
            'totalRequests',
            'pendingRequests',
            'pendingApprovals',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'approvedProducts'
        ));
    }

    public function provider()
    {
        $requests = StationaryRequest::where(function ($q) {
                $q->where('status', 'trust_approved')
                  ->orWhere('status', 'sent_to_provider');
            })
            ->select('id', 'department_id', 'requested_by', 'status', 'total_amount', 'created_at')
            ->latest()
            ->paginate(15);

        $totalRequests     = StationaryRequest::count();
        $pendingRequests   = StationaryRequest::where('status', 'trust_approved')->count();
        $sentRequests      = StationaryRequest::where('status', 'sent_to_provider')->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();
        $rejectedRequests  = StationaryRequest::where('status', 'rejected')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', [
                    'hod_approved', 'principal_approved', 'trust_approved',
                    'sent_to_provider', 'completed'
                ]);
            });
        })->select('id', 'name')->paginate(15);

        return view('dashboards.provider', compact(
            'requests',
            'totalRequests',
            'pendingRequests',
            'sentRequests',
            'completedRequests',
            'rejectedRequests',
            'approvedProducts'
        ));
    }
}