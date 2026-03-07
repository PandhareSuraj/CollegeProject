@props([
    'variant' => 'primary',
    'size' => 'md',
    'icon' => null,
    'class' => '',
])

@php
    $variants = [
        'primary' => 'bg-blue-600 hover:bg-blue-700 dark:bg-blue-600 dark:hover:bg-blue-500 text-white',
        'secondary' => 'border border-gray-300 dark:border-gray-400 bg-white dark:bg-gray-200 text-gray-900 dark:text-gray-900 hover:bg-gray-50 dark:hover:bg-gray-300',
        'danger' => 'bg-red-600 hover:bg-red-700 dark:bg-red-600 dark:hover:bg-red-500 text-white',
        'success' => 'bg-green-600 hover:bg-green-700 dark:bg-green-600 dark:hover:bg-green-500 text-white',
        'warning' => 'bg-amber-600 hover:bg-amber-700 dark:bg-amber-600 dark:hover:bg-amber-500 text-white',
    ];

    $sizes = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
        'xl' => 'px-8 py-4 text-xl',
    ];
@endphp

<button @class([
    'inline-flex items-center justify-center gap-2 rounded-lg font-medium transition-colors duration-200',
    'focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-gray-200',
    $variants[$variant] ?? $variants['primary'],
    $sizes[$size] ?? $sizes['md'],
    $class,
])>
    @if($icon)
        <span class="inline-flex">
            {!! $icon !!}
        </span>
    @endif
    {{ $slot }}
</button>
