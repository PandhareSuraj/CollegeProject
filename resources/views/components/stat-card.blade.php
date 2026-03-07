@props([
    'icon'        => null,
    'title'       => '',
    'value'       => '0',
    'description' => '',
    'trend'       => null,
    'trendUp'     => true,
    'badge'       => null,
    'badgeColor'  => 'blue',
    'bgColor'     => 'blue',
    'progress'    => null,   {{-- 0-100 number, shows colored bar at bottom --}}
])

@php
/* Icon container colors — matches reference: soft bg, colored icon */
$iconColors = [
    'blue'   => ['bg' => 'var(--color-blue-bg)',   'text' => 'var(--color-blue-text)'],
    'amber'  => ['bg' => 'var(--color-amber-bg)',  'text' => 'var(--color-amber-text)'],
    'green'  => ['bg' => 'var(--color-green-bg)',  'text' => 'var(--color-green-text)'],
    'indigo' => ['bg' => 'var(--color-indigo-bg)', 'text' => 'var(--color-indigo-text)'],
    'orange' => ['bg' => 'var(--color-orange-bg)', 'text' => 'var(--color-orange-text)'],
    'red'    => ['bg' => 'var(--color-red-bg)',    'text' => 'var(--color-red-text)'],
    'purple' => ['bg' => 'var(--color-purple-bg)', 'text' => 'var(--color-purple-text)'],
];

/* Solid progress bar colors */
$barColors = [
    'blue'   => '#3b82f6',
    'amber'  => '#f59e0b',
    'green'  => '#22c55e',
    'indigo' => '#6366f1',
    'orange' => '#f97316',
    'red'    => '#ef4444',
    'purple' => '#a855f7',
];

/* Badge colors */
$badgeStyles = [
    'blue'   => 'background:rgba(59,130,246,0.12);color:#3b82f6;',
    'green'  => 'background:rgba(34,197,94,0.12);color:#16a34a;',
    'red'    => 'background:rgba(239,68,68,0.12);color:#dc2626;',
    'amber'  => 'background:rgba(245,158,11,0.12);color:#d97706;',
    'orange' => 'background:rgba(249,115,22,0.12);color:#ea580c;',
    'indigo' => 'background:rgba(99,102,241,0.12);color:#4f46e5;',
    'purple' => 'background:rgba(168,85,247,0.12);color:#9333ea;',
];

$ic   = $iconColors[$bgColor]   ?? $iconColors['blue'];
$bar  = $barColors[$bgColor]    ?? $barColors['blue'];
$bdg  = $badgeStyles[$badgeColor] ?? $badgeStyles['blue'];
@endphp

<div style="
    background-color: var(--theme-card-bg);
    border: 1px solid var(--theme-card-border);
    color: var(--theme-text-primary);
    border-radius: 14px;
    box-shadow: 0 1px 4px var(--theme-shadow);
    transition: box-shadow 0.25s, transform 0.2s;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-height: 160px;
" onmouseover="this.style.boxShadow='0 8px 24px var(--theme-shadow-lg)';this.style.transform='translateY(-2px)'"
   onmouseout="this.style.boxShadow='0 1px 4px var(--theme-shadow)';this.style.transform='translateY(0)'">

    {{-- Card Body --}}
    <div style="padding: 22px 24px 16px; flex:1; display:flex; flex-direction:column; gap:10px;">

        {{-- Top row: label + icon --}}
        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
            <p style="
                font-size: 0.72rem;
                font-weight: 700;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: var(--theme-text-tertiary);
                margin: 0;
                line-height: 1;
            ">{{ $title }}</p>

            @if($icon)
            <div style="
                display: inline-flex;
                align-items: center;
                justify-content: center;
                width: 36px; height: 36px;
                border-radius: 10px;
                flex-shrink: 0;
                background-color: {{ $ic['bg'] }};
                color: {{ $ic['text'] }};
            ">{!! $icon !!}</div>
            @endif
        </div>

        {{-- Big number --}}
        <p style="
            font-size: 2.6rem;
            font-weight: 800;
            line-height: 1;
            margin: 0;
            color: var(--theme-text-primary);
            letter-spacing: -0.02em;
        ">{{ $value }}</p>

        {{-- Description --}}
        @if($description)
        <p style="
            font-size: 0.82rem;
            color: var(--theme-text-secondary);
            margin: 0;
            line-height: 1.4;
        ">{{ $description }}</p>
        @endif

        {{-- Bottom row: trend + badge --}}
        @if($trend || $badge)
        <div style="display:flex; align-items:center; justify-content:space-between; margin-top:auto; padding-top:4px;">
            @if($trend)
            <span style="
                display:inline-flex;
                align-items:center;
                gap:4px;
                font-size:0.78rem;
                font-weight:600;
                color: {{ $trendUp ? '#16a34a' : '#dc2626' }};
            ">
                <svg style="width:14px;height:14px;" fill="currentColor" viewBox="0 0 20 20">
                    @if($trendUp)
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 9.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"/>
                    @else
                    <path fill-rule="evenodd" d="M12 13a1 1 0 100 2h5a1 1 0 001-1V9a1 1 0 10-2 0v2.586l-4.293-4.293a1 1 0 00-1.414 0L8 10.586 3.707 6.293a1 1 0 00-1.414 1.414l5 5a1 1 0 001.414 0L11 9.414 14.586 13H12z" clip-rule="evenodd"/>
                    @endif
                </svg>
                {{ $trend }}
            </span>
            @endif

            @if($badge)
            <span style="
                display:inline-block;
                font-size:0.72rem;
                font-weight:700;
                padding:3px 10px;
                border-radius:9999px;
                {{ $bdg }}
            ">{{ $badge }}</span>
            @endif
        </div>
        @endif

    </div>

    {{-- Colored progress bar at bottom (like reference image) --}}
    @if($progress !== null)
    <div style="height:4px; background-color: var(--theme-border-primary); width:100%;">
        <div style="
            height:100%;
            width:{{ min(100, max(0, $progress)) }}%;
            background-color: {{ $bar }};
            border-radius:0 2px 2px 0;
            transition: width 0.6s ease;
        "></div>
    </div>
    @else
    {{-- Thin accent line at bottom even without progress --}}
    <div style="height:3px; background-color: {{ $bar }}; width:100%;"></div>
    @endif
</div>