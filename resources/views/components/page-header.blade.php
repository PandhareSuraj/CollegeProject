@props([
    'title' => '',
    'icon' => null,
    'description' => null,
])

<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-3xl font-bold theme-text-primary flex items-center gap-3">
            @if($icon)
                {!! $icon !!}
            @else
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z" />
                </svg>
            @endif
            {{ $title }}
        </h1>
        @if($description)
            <p class="mt-2 text-sm theme-text-secondary">{{ $description }}</p>
        @endif
    </div>
    @if($slot->isNotEmpty())
        <div class="flex gap-3 items-center">
            {{ $slot }}
        </div>
    @endif
</div>
