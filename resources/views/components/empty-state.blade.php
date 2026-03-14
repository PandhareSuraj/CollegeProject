@props([
    'icon' => null,
    'title' => 'No data found',
    'message' => 'There is no data to display.',
    'action' => null,
    'actionLabel' => 'Create Item',
    'colspan' => 5,
])

<tr>
    <td colspan="{{ $colspan }}" class="px-6 py-12 text-center">
        <div class="flex flex-col items-center gap-4">
            @if($icon)
                <div class="text-gray-400 dark:text-gray-600">
                    {!! $icon !!}
                </div>
            @else
                <svg class="w-12 h-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
            @endif
            
            <div>
                <h3 class="text-lg font-semibold theme-text-primary">{{ $title }}</h3>
                <p class="text-sm theme-text-secondary mt-1">{{ $message }}</p>
            </div>

            @if($action)
                <a href="{{ $action }}" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    {{ $actionLabel }}
                </a>
            @else
                {{ $slot }}
            @endif
        </div>
    </td>
</tr>
