@props([
    'icon' => null,
    'title' => '',
    'value' => '0',
    'description' => '',
    'trend' => null,
    'trendColor' => 'green',
    'bgColor' => 'blue'
])

<div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border border-gray-200 dark:border-gray-700">
    <div class="flex items-start justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $title }}</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $value }}</p>
            @if($description)
                <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">{{ $description }}</p>
            @endif
            @if($trend)
                <div class="flex items-center gap-1 mt-3">
                    <svg class="w-4 h-4 text-{{ $trendColor }}-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414-1.414L13.586 7H12z" clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium text-{{ $trendColor }}-600 dark:text-{{ $trendColor }}-400">{{ $trend }}</span>
                </div>
            @endif
        </div>
        @if($icon)
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-{{ $bgColor }}-100 dark:bg-{{ $bgColor }}-900 dark:bg-opacity-30">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
