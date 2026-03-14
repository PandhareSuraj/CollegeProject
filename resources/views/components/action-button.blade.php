@props([
    'href' => null,
    'type' => 'primary',
    'size' => 'md',
    'icon' => null,
    'class' => '',
    'disabled' => false,
])

@php
    $types = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition',
        'secondary' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 transition',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 dark:hover:bg-red-800 transition',
        'success' => 'bg-green-600 text-white hover:bg-green-700 dark:hover:bg-green-800 transition',
        'warning' => 'bg-amber-600 text-white hover:bg-amber-700 dark:hover:bg-amber-800 transition',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 rounded-lg font-medium ' . 
               ($types[$type] ?? $types['primary']) . ' ' . 
               ($sizes[$size] ?? $sizes['md']) . ' ' .
               $class;

    if ($disabled) {
        $classes .= ' opacity-50 cursor-not-allowed pointer-events-none';
    }
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="inline-flex">
                {!! $icon !!}
            </span>
        @endif
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'disabled' => $disabled]) }}>
        @if($icon)
            <span class="inline-flex">
                {!! $icon !!}
            </span>
        @endif
        {{ $slot }}
    </button>
@endif
