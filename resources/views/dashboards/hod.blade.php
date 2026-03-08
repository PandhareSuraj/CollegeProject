@extends('layouts.app')

@section('title', 'HOD Dashboard')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
    </svg>
    <h2 class="navbar-title">HOD Dashboard</h2>
</div>
@endsection

@section('content')
<div style="background-color: var(--bg-body); min-height: 100%;">

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:32px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div style="display:flex; align-items:center; gap:12px;">
            <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#4338ca);display:flex;align-items:center;justify-content:center;">
                <svg style="width:22px;height:22px;color:#fff;" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
            </div>
            <div>
                <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);margin:0;letter-spacing:-0.02em;">HOD Dashboard</h1>
                <p style="font-size:0.85rem;color:var(--text-secondary);margin:0;">Department head overview</p>
            </div>
        </div>
        <a href="{{ route('requests.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:0.85rem;font-weight:600;background:#6366f1;color:#fff;text-decoration:none;">
            <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
            View All Requests
        </a>
    </div>

    {{-- KEY METRICS --}}
    <p class="section-eyebrow">KEY METRICS</p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:32px;">
        <x-stat-card title="Total Requests" :value="$totalRequests" description="All department requests" bgColor="indigo" badge="All time" badgeColor="indigo" :progress="100"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M4 4a2 2 0 012-2h6a2 2 0 012 2v12a1 1 0 110 2h-7a1 1 0 110-2h7V4z'/></svg>" />
        <x-stat-card title="Pending" :value="$pendingRequests" description="Awaiting your approval" bgColor="amber" badge="Pending" badgeColor="amber"
            :progress="$totalRequests > 0 ? round(($pendingRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z' clip-rule='evenodd'/></svg>" />
        <x-stat-card title="Approved" :value="$approvedRequests" description="Requests you approved" bgColor="green" badge="Approved" badgeColor="green"
            :progress="$totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>" />
        <x-stat-card title="Rejected" :value="$rejectedRequests" description="Requests rejected" bgColor="red" badge="Rejected" badgeColor="red" :trendUp="false"
            :progress="$totalRequests > 0 ? round(($rejectedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z' clip-rule='evenodd'/></svg>" />
    </div>

    {{-- PENDING APPROVALS TABLE --}}
    <p class="section-eyebrow">PENDING APPROVALS</p>
    <div class="dash-table-wrap">
        <div class="dash-table-head">
            <div style="width:8px;height:8px;border-radius:50%;background:#f59e0b;flex-shrink:0;"></div>
            <span class="dash-table-title">Pending Requests</span>
            <span class="dash-table-count">{{ $pendingApprovals ?? 0 }} items</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background-color:var(--table-header-bg);border-bottom:1px solid var(--table-border);">
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Request</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Requested By</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Amount</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Date</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests ?? [] as $request)
                    <tr class="dash-tr" style="background-color:var(--table-row-bg);">
                        <td style="padding:14px 20px;font-size:0.875rem;font-weight:600;color:var(--text-primary);">{{ $request->title ?? 'Request #'.$request->id }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->user->name ?? '—' }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-primary);">₹{{ number_format($request->total_amount ?? 0, 2) }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->created_at->format('d M Y') }}</td>
                        <td style="padding:14px 20px;">
                            <a href="{{ route('requests.show', $request->id) }}" style="font-size:0.8rem;font-weight:600;padding:5px 12px;border-radius:8px;background:rgba(99,102,241,0.12);color:#6366f1;text-decoration:none;">Review</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;color:var(--text-secondary);font-size:0.9rem;">No pending requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection