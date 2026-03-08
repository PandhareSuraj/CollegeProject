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
        })->select('id','name')->paginate(15);

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
            ->select('id','department_id','requested_by','status','total_amount','created_at')
            ->latest()
            ->paginate(15);

        // Compute totals via queries so pagination doesn't affect totals
        $totalRequests = StationaryRequest::where('requested_by', $user->id)->count();
        $pendingRequests = StationaryRequest::where('requested_by', $user->id)->where('status', 'pending')->count();
        $approvedRequests = StationaryRequest::where('requested_by', $user->id)->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved'])->count();
        $completedRequests = StationaryRequest::where('requested_by', $user->id)->where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->select('id','name')->paginate(15);

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
            ->select('id','department_id','requested_by','status','total_amount','created_at')
            ->latest()
            ->paginate(15);

        $totalRequests = StationaryRequest::count();
    // number used in stat card
    $pendingRequests = StationaryRequest::where('status', 'pending')->count();
    // collection used for the table (paginated)
    $pendingApprovals = $requests;
        $approvedRequests = StationaryRequest::whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved'])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();
    $rejectedRequests = StationaryRequest::where('status', 'rejected')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->select('id','name')->paginate(15);

        return view('dashboards.hod', compact(
            'requests',
            'totalRequests',
            'pendingApprovals',
            'pendingRequests',
            'approvedRequests',
            'completedRequests',
            'rejectedRequests',
            'approvedProducts'
        ));
    }

    public function principal()
    {
        $requests = StationaryRequest::where('status', 'hod_approved')
            ->select('id','department_id','requested_by','status','total_amount','created_at')
            ->latest()
            ->paginate(15);

        $totalRequests = StationaryRequest::count();
        $pendingApprovals = StationaryRequest::where('status', 'hod_approved')->count();
        $approvedRequests = StationaryRequest::whereIn('status', ['principal_approved', 'trust_approved'])->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->select('id','name')->paginate(15);

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
            ->select('id','department_id','requested_by','status','total_amount','created_at')
            ->latest()
            ->paginate(15);

        $totalRequests = StationaryRequest::count();
        $pendingApprovals = StationaryRequest::where('status', 'principal_approved')->count();
        $approvedRequests = StationaryRequest::where('status', 'trust_approved')->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->select('id','name')->paginate(15);

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
        $requests = StationaryRequest::where(function ($q) {
                $q->where('status', 'trust_approved')
                  ->orWhere('status', 'sent_to_provider');
            })
            ->select('id','department_id','requested_by','status','total_amount','created_at')
            ->latest()
            ->paginate(15);

        $totalRequests = StationaryRequest::count();
        $sentRequests = StationaryRequest::where('status', 'sent_to_provider')->count();
        $completedRequests = StationaryRequest::where('status', 'completed')->count();

        $approvedProducts = Product::whereHas('requestItems', function ($q) {
            $q->whereHas('request', function ($rq) {
                $rq->whereIn('status', ['hod_approved', 'principal_approved', 'trust_approved', 'sent_to_provider', 'completed']);
            });
        })->select('id','name')->paginate(15);

        return view('dashboards.provider', compact(
            'requests',
            'totalRequests',
            'sentRequests',
            'completedRequests'
            ,'approvedProducts'
        ));
    }
}
