@props([
    'size' => 'md',
    'shadow' => true,
    'hover' => false,
    'border' => true,
    'variant' => 'default',
])

@php
    $sizeClass = match($size) {
        'sm' => 'p-4',
        'lg' => 'p-8',
        default => 'p-6',
    };

    $shadowClass = $shadow ? 'shadow-lg' : 'shadow-none';
    $hoverClass = $hover ? 'hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5' : '';
    $borderClass = $border ? 'border' : 'border-0';
    
    $variantClass = match($variant) {
        'secondary' => 'theme-bg-secondary',
        'tertiary' => 'theme-bg-tertiary',
        default => '',
    };
@endphp

<div class="theme-card {{ $sizeClass }} {{ $shadowClass }} {{ $hoverClass }} {{ $borderClass }} {{ $variantClass }} rounded-xl">
    {{ $slot }}
</div>

