@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
        <path d="M10.5 1.5H5.75a2.25 2.25 0 00-2.25 2.25v12a2.25 2.25 0 002.25 2.25h8.5a2.25 2.25 0 002.25-2.25V6m-11-4h4v4m0-4l4 4"/>
    </svg>
    <h2 class="navbar-title">Teacher Dashboard</h2>
</div>
@endsection

@section('content')
<div style="padding: 32px 36px; background-color: var(--bg-body); min-height: 100%;">

    {{-- PAGE HEADER --}}
    <div style="margin-bottom:32px;">
        <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
            <div style="display:flex;align-items:center;gap:12px;">
                <div style="width:42px;height:42px;border-radius:12px;background:linear-gradient(135deg,#22c55e,#15803d);display:flex;align-items:center;justify-content:center;">
                    <svg style="width:22px;height:22px;color:#fff;" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zm5.99 7.176A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h1 style="font-size:1.75rem;font-weight:800;color:var(--text-primary);margin:0;letter-spacing:-0.02em;">Teacher Dashboard</h1>
                    <p style="font-size:0.85rem;color:var(--text-secondary);margin:0;">Your request overview</p>
                </div>
            </div>
            <a href="{{ route('requests.create') }}" style="display:inline-flex;align-items:center;gap:6px;padding:9px 18px;border-radius:10px;font-size:0.85rem;font-weight:600;background:#22c55e;color:#fff;text-decoration:none;">
                <svg style="width:16px;height:16px;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"/></svg>
                New Request
            </a>
        </div>
    </div>

    {{-- KEY METRICS --}}
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">KEY METRICS</p>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:18px;margin-bottom:32px;">

        <x-stat-card
            title="My Requests"
            :value="$totalRequests"
            description="Total submitted"
            bgColor="green"
            badge="All time"
            badgeColor="green"
            :progress="100"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M4 4a2 2 0 012-2h6a2 2 0 012 2v12a1 1 0 110 2h-7a1 1 0 110-2h7V4z'/></svg>"
        />

        <x-stat-card
            title="Pending"
            :value="$pendingRequests"
            description="Awaiting approval"
            bgColor="amber"
            badge="In review"
            badgeColor="amber"
            :progress="$totalRequests > 0 ? round(($pendingRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z' clip-rule='evenodd'/></svg>"
        />

        <x-stat-card
            title="Approved"
            :value="$approvedRequests"
            description="Successfully approved"
            bgColor="blue"
            badge="Approved"
            badgeColor="blue"
            :progress="$totalRequests > 0 ? round(($approvedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z' clip-rule='evenodd'/></svg>"
        />

        <x-stat-card
            title="Completed"
            :value="$completedRequests"
            description="Order delivered"
            bgColor="indigo"
            badge="Done"
            badgeColor="indigo"
            :progress="$totalRequests > 0 ? round(($completedRequests / $totalRequests) * 100) : 0"
            icon="<svg style='width:18px;height:18px;' fill='currentColor' viewBox='0 0 20 20'><path d='M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z'/></svg>"
        />
    </div>

    {{-- RECENT REQUESTS TABLE --}}
    <p style="font-size:0.7rem;font-weight:700;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-secondary);margin:0 0 14px;">RECENT REQUESTS</p>
    <div style="background-color:var(--bg-card);border:1px solid var(--border-color);border-radius:14px;box-shadow:0 1px 4px rgba(0,0,0,0.08);overflow:hidden;">
        <div style="padding:18px 24px;border-bottom:1px solid var(--border-color);display:flex;align-items:center;gap:10px;">
            <div style="width:8px;height:8px;border-radius:50%;background:#22c55e;"></div>
            <span style="font-size:0.95rem;font-weight:700;color:var(--text-primary);">My Recent Requests</span>
            <a href="{{ route('requests.index') }}" style="margin-left:auto;font-size:0.8rem;color:#3b82f6;text-decoration:none;font-weight:600;">View all →</a>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr style="background-color:var(--bg-body);border-bottom:1px solid var(--border-color);">
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Request</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Amount</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Status</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);">Date</th>
                        <th style="padding:12px 20px;text-align:left;font-size:0.72rem;font-weight:700;text-transform:uppercase;letter-spacing:0.06em;color:var(--text-secondary);"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse(isset($recentRequests) ? $recentRequests : [] as $request)
                    <tr style="border-bottom:1px solid var(--border-color);transition:background 0.15s;" onmouseover="this.style.background='var(--bg-body)'" onmouseout="this.style.background='transparent'">
                        <td style="padding:14px 20px;font-size:0.875rem;font-weight:600;color:var(--text-primary);">{{ $request->title ?? 'Request #'.$request->id }}</td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-primary);">₹{{ number_format($request->total_amount ?? 0, 2) }}</td>
                        <td style="padding:14px 20px;">
                            @php
                                $statusColors = ['pending'=>['bg'=>'rgba(245,158,11,0.12)','color'=>'#d97706'],'approved'=>['bg'=>'rgba(34,197,94,0.12)','color'=>'#16a34a'],'rejected'=>['bg'=>'rgba(239,68,68,0.12)','color'=>'#dc2626'],'completed'=>['bg'=>'rgba(99,102,241,0.12)','color'=>'#6366f1']];
                                $sc = $statusColors[$request->status] ?? ['bg'=>'rgba(148,163,184,0.12)','color'=>'#64748b'];
                            @endphp
                            <span style="display:inline-block;font-size:0.75rem;font-weight:600;padding:3px 10px;border-radius:9999px;background:{{ $sc['bg'] }};color:{{ $sc['color'] }};">{{ ucfirst($request->status) }}</span>
                        </td>
                        <td style="padding:14px 20px;font-size:0.875rem;color:var(--text-secondary);">{{ $request->created_at->format('d M Y') }}</td>
                        <td style="padding:14px 20px;">
                            <a href="{{ route('requests.show', $request->id) }}" style="font-size:0.8rem;font-weight:600;padding:5px 12px;border-radius:8px;background:rgba(34,197,94,0.10);color:#16a34a;text-decoration:none;">View</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;color:var(--text-secondary);font-size:0.9rem;">No requests yet. Create your first one!</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection