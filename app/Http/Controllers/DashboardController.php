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
        $totalRequests = StationaryRequest::count();
        $pendingRequests = StationaryRequest::where('status', 'pending')->count();
        $approvedRequests = StationaryRequest::whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved'])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();
        $rejectedRequests = StationaryRequest::where('status', 'rejected')->count();
        $totalAmount = StationaryRequest::sum('total_amount') ?? 0;

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->get();

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
        $requests = StationaryRequest::where('requested_by', $user->id)->latest()->get();
        $totalRequests = $requests->count();
        $pendingRequests = $requests->where('status', 'pending')->count();
        $approvedRequests = $requests->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved'])->count();
        $completedRequests = $requests->where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->get();

        return view('dashboards.teacher', compact(
            'requests',
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'completedRequests'
            ,'approvedProducts'
        ));
    }

    public function hod()
    {
        $user = Auth::user();

        $requests = StationaryRequest::where('status', 'pending')
            ->latest()
            ->get();

        $totalRequests = StationaryRequest::count();
        $pendingApprovals = $requests->count();
        $approvedRequests = StationaryRequest::whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved'])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->get();

        return view('dashboards.hod', compact(
            'requests',
            'totalRequests',
            'pendingApprovals',
            'approvedRequests',
            'completedRequests'
            ,'approvedProducts'
        ));
    }

    public function principal()
    {
        $requests = StationaryRequest::where('status', 'hod_approved')
            ->latest()
            ->get();

        $totalRequests = StationaryRequest::count();
        $pendingApprovals = $requests->count();
        $approvedRequests = StationaryRequest::whereIn('status', ['principal_approved', 'trust_approved'])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->get();

        return view('dashboards.principal', compact(
            'requests',
            'totalRequests',
            'pendingApprovals',
            'approvedRequests',
            'completedRequests'
            ,'approvedProducts'
        ));
    }

    public function trustHead()
    {
        $requests = StationaryRequest::where('status', 'principal_approved')
            ->latest()
            ->get();

        $totalRequests = StationaryRequest::count();
        $pendingApprovals = $requests->count();
        $approvedRequests = StationaryRequest::where('status', 'trust_approved')->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->get();

        return view('dashboards.trust-head', compact(
            'requests',
            'totalRequests',
            'pendingApprovals',
            'approvedRequests',
            'completedRequests'
            ,'approvedProducts'
        ));
    }

    public function provider()
    {
        $requests = StationaryRequest::where('status', 'trust_approved')
            ->orWhere('status', 'sent_to_provider')
            ->latest()
            ->get();

        $totalRequests = $requests->count();
        $sentRequests = $requests->where('status', 'sent_to_provider')->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->get();

        return view('dashboards.provider', compact(
            'requests',
            'totalRequests',
            'sentRequests',
            'completedRequests'
            ,'approvedProducts'
        ));
    }
}
