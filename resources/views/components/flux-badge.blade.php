@props([
    'color' => 'blue',
    'class' => '',
])

@php
    $colors = [
        'blue' => 'bg-blue-100 dark:bg-blue-900 dark:bg-opacity-30 text-blue-800 dark:text-blue-300',
        'green' => 'bg-green-100 dark:bg-green-900 dark:bg-opacity-30 text-green-800 dark:text-green-300',
        'red' => 'bg-red-100 dark:bg-red-900 dark:bg-opacity-30 text-red-800 dark:text-red-300',
        'amber' => 'bg-amber-100 dark:bg-amber-900 dark:bg-opacity-30 text-amber-800 dark:text-amber-300',
        'purple' => 'bg-purple-100 dark:bg-purple-900 dark:bg-opacity-30 text-purple-800 dark:text-purple-300',
        'orange' => 'bg-orange-100 dark:bg-orange-900 dark:bg-opacity-30 text-orange-800 dark:text-orange-300',
        'indigo' => 'bg-indigo-100 dark:bg-indigo-900 dark:bg-opacity-30 text-indigo-800 dark:text-indigo-300',
        'teal' => 'bg-teal-100 dark:bg-teal-900 dark:bg-opacity-30 text-teal-800 dark:text-teal-300',
        'pink' => 'bg-pink-100 dark:bg-pink-900 dark:bg-opacity-30 text-pink-800 dark:text-pink-300',
    ];
@endphp

<span @class([
    'inline-block px-3 py-1 rounded-full text-xs font-medium',
    $colors[$color] ?? $colors['blue'],
    $class,
])>
    {{ $slot }}
</span>
