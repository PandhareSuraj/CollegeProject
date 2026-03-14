@props([
    'status' => '',
    'statusMap' => [],
])

@php
    // Default status color mapping
    $defaultMap = [
        'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'dark_bg' => 'dark:bg-yellow-900', 'dark_text' => 'dark:text-yellow-300', 'label' => 'Pending'],
        'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'dark_bg' => 'dark:bg-blue-900', 'dark_text' => 'dark:text-blue-300', 'label' => 'Processing'],
        'supplied' => ['bg' => 'bg-cyan-100', 'text' => 'text-cyan-800', 'dark_bg' => 'dark:bg-cyan-900', 'dark_text' => 'dark:text-cyan-300', 'label' => 'Supplied'],
        'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'dark_bg' => 'dark:bg-green-900', 'dark_text' => 'dark:text-green-300', 'label' => 'Completed'],
        'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dark_bg' => 'dark:bg-red-900', 'dark_text' => 'dark:text-red-300', 'label' => 'Cancelled'],
        'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'dark_bg' => 'dark:bg-red-900', 'dark_text' => 'dark:text-red-300', 'label' => 'Rejected'],
    ];

    $map = array_merge($defaultMap, $statusMap);
    $config = $map[strtolower($status)] ?? $defaultMap['pending'];
@endphp

<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $config['bg'] }} {{ $config['text'] }} {{ $config['dark_bg'] }} {{ $config['dark_text'] }}">
    {{ $config['label'] ?? ucfirst($status) }}
</span>
