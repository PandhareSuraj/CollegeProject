@props([
    'type' => 'submit',
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'class' => '',
    'disabled' => false,
])

@php
    $variants = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-500 focus:ring-blue-500',
        'secondary' => 'border border-gray-300 text-gray-700 bg-white hover:bg-gray-50 dark:bg-gray-700 dark:text-white focus:ring-gray-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 dark:hover:bg-red-500 focus:ring-red-500',
        'success' => 'bg-green-600 text-white hover:bg-green-700 dark:hover:bg-green-500 focus:ring-green-500',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];
@endphp

<button 
    type="{{ $type }}"
    {{ $attributes->merge([
        'class' => 'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors duration-200 ' .
                   'focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-900 ' .
                   ($variants[$variant] ?? $variants['primary']) . ' ' .
                   ($sizes[$size] ?? $sizes['md']) . ' ' .
                   ($disabled ? 'opacity-50 cursor-not-allowed' : '') . ' ' .
                   $class,
    ]) }}
    @if($disabled) disabled @endif
>
    @if($icon)
        <span class="inline-flex">
            {!! $icon !!}
        </span>
    @endif
    {{ $slot }}
</button>
