{{-- ============================================================
     PRINCIPAL DASHBOARD
     Save to: resources/views/dashboard/principal.blade.php
     ============================================================ --}}

@extends('layouts.app')
@section('title', 'Principal Dashboard')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.5 1.5H5a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-9.5m-7-4v4m0-4l4 4"/>
    </svg>
    <h2 class="navbar-title">Principal Dashboard</h2>
</div>
@endsection

@section('content')
<div style="padding: 32px 36px; background-color: var(--bg-body); min-height: 100%;">

    <div style="margin-bottom:32px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:22px;height:22px;color:#fff;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);margin:0;letter-spacing:-0.02em;">Principal Dashboard</h1>
                    <p style="font-size:0.85rem;color:var(--text-secondary);margin:0;">Final approval authority</p>
                </div>
            </div>
            <a href="{{ route('requests.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:0.85rem;font-weight:600;background:#f59e0b;color:#fff;text-decoration:none;">
                View Pending Approvals
            </a>
        </div>
    </div>

    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">KEY METRICS</p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:32px;">

        <x-stat-card title="Total Requests" :value="$totalRequests" description="Across all departments" bgColor="amber" badge="All time" badgeColor="amber" :progress="100"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M4 4a2 2 0 012-2h6a2 2 0 012 2v12a1 1 0 110 2h-7a1 1 0 110-2h7V4z'/></svg>" />

        <x-stat-card title="HOD Approved" :value="$hodApprovedRequests ?? 0" description="Awaiting your decision" bgColor="blue" badge="Pending" badgeColor="blue"
            :progress="$totalRequests > 0 ? round((($hodApprovedRequests ?? 0) / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z' clip-rule='evenodd'/></svg>" />

        <x-stat-card title="Approved" :value="$approvedRequests" description="Requests you approved" bgColor="green" badge="Approved" badgeColor="green"
            :progress="$totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>" />

        <x-stat-card title="Rejected" :value="$rejectedRequests" description="Requests rejected" bgColor="red" badge="Rejected" badgeColor="red" :trendUp="false"
            :progress="$totalRequests > 0 ? round(($rejectedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z' clip-rule='evenodd'/></svg>" />
    </div>

    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">HOD-APPROVED REQUESTS</p>
    <div style="background-color:var(--bg-card);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border-color);display:flex;align-items:center;gap:10px;">
            <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;"></div>
            <span style="font-size:0.95rem;font-weight:700;color:var(--text-primary);">Awaiting Your Approval</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background-color:var(--bg-body);border-bottom:1px solid var(--border-color);">
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Request</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Teacher</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Dept</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Amount</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(isset($hodApprovedList) ? $hodApprovedList : [] as $request)
                    <tr style="border-bottom:1px solid var(--border-color);" onmouseover="this.style.background='var(--bg-body)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-size:0.875rem;font-weight:600;color:var(--text-primary);">{{ $request->title ?? 'Request #'.$request->id }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->user->name ?? '—' }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->department->name ?? '—' }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-primary);">₹{{ number_format($request->total_amount ?? 0, 2) }}</td>
                        <td style="padding:14px 20px;">
                            <a href="{{ route('requests.show', $request->id) }}" style="font-size:0.8rem;font-weight:600;padding:5px 12px;border-radius:8px;background:rgba(245,158,11,0.12);color:#d97706;text-decoration:none;">Review</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;color:var(--text-secondary);">No requests awaiting your approval.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection