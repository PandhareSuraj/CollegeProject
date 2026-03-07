{{-- ============================================================
     PROVIDER DASHBOARD
     Save to: resources/views/dashboard/provider.blade.php
     ============================================================ --}}

@extends('layouts.app')
@section('title', 'Provider Dashboard')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
    </svg>
    <h2 class="navbar-title">Provider Dashboard</h2>
</div>
@endsection

@section('content')
<div style="padding: 32px 36px; background-color: var(--bg-body); min-height: 100%;">

    <div style="margin-bottom:32px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#f97316,#ea580c);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:22px;height:22px;color:#fff;" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z"/></svg>
                </div>
                <div>
                    <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);margin:0;letter-spacing:-0.02em;">Provider Dashboard</h1>
                    <p style="font-size:0.85rem;color:var(--text-secondary);margin:0;">Supply request management</p>
                </div>
            </div>
            <a href="{{ route('requests.index') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:0.85rem;font-weight:600;background:#f97316;color:#fff;text-decoration:none;">
                View Supply Requests
            </a>
        </div>
    </div>

    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">KEY METRICS</p>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:32px;">

        <x-stat-card title="Total Requests" :value="$totalRequests" description="All supply requests" bgColor="orange" badge="All time" badgeColor="orange" :progress="100"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M4 4a2 2 0 012-2h6a2 2 0 012 2v12a1 1 0 110 2h-7a1 1 0 110-2h7V4z'/></svg>" />

        <x-stat-card title="Pending Supply" :value="$pendingRequests" description="Waiting to be fulfilled" bgColor="amber" badge="Pending" badgeColor="amber"
            :progress="$totalRequests > 0 ? round(($pendingRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z' clip-rule='evenodd'/></svg>" />

        <x-stat-card title="Completed" :value="$completedRequests" description="Successfully supplied" bgColor="green" badge="Delivered" badgeColor="green"
            :progress="$totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z'/></svg>" />
    </div>

    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">SUPPLY REQUESTS</p>
    <div style="background-color:var(--bg-card);border:1px solid var(--border-color);border-radius:14px;overflow:hidden;">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border-color);display:flex;align-items:center;gap:10px;">
            <div style="width:8px;height:8px;border-radius:50%;background:#f97316;"></div>
            <span style="font-size:0.95rem;font-weight:700;color:var(--text-primary);">Active Supply Requests</span>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background-color:var(--bg-body);border-bottom:1px solid var(--border-color);">
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Request</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Dept</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Amount</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Status</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(isset($supplyRequests) ? $supplyRequests : [] as $request)
                    <tr style="border-bottom:1px solid var(--border-color);" onmouseover="this.style.background='var(--bg-body)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-size:0.875rem;font-weight:600;color:var(--text-primary);">{{ $request->title ?? 'Request #'.$request->id }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->department->name ?? '—' }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-primary);">₹{{ number_format($request->total_amount ?? 0, 2) }}</td>
                        <td style="padding:14px 20px;">
                            @php $sc = $request->status === 'completed' ? ['bg'=>'rgba(34,197,94,0.12)','c'=>'#16a34a'] : ['bg'=>'rgba(249,115,22,0.12)','c'=>'#ea580c']; @endphp
                            <span style="display:inline-block;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:9999px;background:{{ $sc['bg'] }};color:{{ $sc['c'] }};">{{ ucfirst($request->status) }}</span>
                        </td>
                        <td style="padding:14px 20px;">
                            <a href="{{ route('requests.show', $request->id) }}" style="font-size:0.8rem;font-weight:600;padding:5px 12px;border-radius:8px;background:rgba(249,115,22,0.10);color:#ea580c;text-decoration:none;">Manage</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;color:var(--text-secondary);">No supply requests at this time.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection