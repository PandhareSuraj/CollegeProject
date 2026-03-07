@extends('layouts.app')
@section('title', 'Trust Head Dashboard')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1a1 1 0 01-1 1H1v-2a6 6 0 0112 0v1a1 1 0 001 1h3a1 1 0 001-1v-1zM6 16a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    <h2 class="navbar-title">Trust Head Dashboard</h2>
</div>
@endsection

@section('content')
<div style="padding: 32px 36px; background-color: var(--bg-body); min-height: 100%;">

    <div style="margin-bottom:32px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#a855f7,#9333ea);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:22px;height:22px;color:#fff;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);margin:0;letter-spacing:-0.02em;">Trust Head Dashboard</h1>
                    <p style="font-size:0.85rem;color:var(--text-secondary);margin:0;">Institution-wide oversight</p>
                </div>
            </div>
            <a href="{{ route('requests.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:0.85rem;font-weight:600;background:#a855f7;color:#fff;text-decoration:none;">
                View All Requests
            </a>
        </div>
    </div>

    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">KEY METRICS</p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:32px;">

        <x-stat-card title="Total Requests" :value="$totalRequests" description="Institution-wide" bgColor="purple" badge="All time" badgeColor="purple" :progress="100"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M4 4a2 2 0 012-2h6a2 2 0 012 2v12a1 1 0 110 2h-7a1 1 0 110-2h7V4z'/></svg>" />

        <x-stat-card title="Principal Approved" :value="$principalApprovedRequests ?? 0" description="Awaiting your approval" bgColor="blue" badge="Pending" badgeColor="blue"
            :progress="$totalRequests > 0 ? round((($principalApprovedRequests ?? 0) / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z' clip-rule='evenodd'/></svg>" />

        <x-stat-card title="Approved" :value="$approvedRequests" description="Trust approved" bgColor="green" badge="Approved" badgeColor="green"
            :progress="$totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>" />

        <x-stat-card title="Total Value" value="₹{{ number_format($totalAmount ?? 0, 2) }}" description="Institution spend" bgColor="indigo" badge="Financial" badgeColor="indigo" :progress="80"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM2 9v7a2 2 0 002 2h12a2 2 0 002-2V9H2zm6 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z'/></svg>" />
    </div>

    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">PRINCIPAL-APPROVED REQUESTS</p>
    <div style="background-color:var(--bg-card);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border-color);display:flex;align-items:center;gap:10px;">
            <div style="width:8px;height:8px;border-radius:50%;background:#a855f7;"></div>
            <span style="font-size:0.95rem;font-weight:700;color:var(--text-primary);">Awaiting Trust Approval</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background-color:var(--bg-body);border-bottom:1px solid var(--border-color);">
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Request</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Department</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Amount</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Date</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(isset($principalApprovedList) ? $principalApprovedList : [] as $request)
                    <tr style="border-bottom:1px solid var(--border-color);" onmouseover="this.style.background='var(--bg-body)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-size:0.875rem;font-weight:600;color:var(--text-primary);">{{ $request->title ?? 'Request #'.$request->id }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->department->name ?? '—' }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-primary);">₹{{ number_format($request->total_amount ?? 0, 2) }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->created_at->format('d M Y') }}</td>
                        <td style="padding:14px 20px;">
                            <a href="{{ route('requests.show', $request->id) }}" style="font-size:0.8rem;font-weight:600;padding:5px 12px;border-radius:8px;background:rgba(168,85,247,0.10);color:#a855f7;text-decoration:none;">Review</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;color:var(--text-secondary);">No requests awaiting trust approval.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection