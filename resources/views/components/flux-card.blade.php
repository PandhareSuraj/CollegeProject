@props([
    'header' => false,
    'body' => true,
    'footer' => false,
    'class' => '',
])

<div class="rounded-lg border shadow-md transition-all duration-300 hover:shadow-lg {{ $class }}"
     style="background-color: var(--theme-card-bg); border-color: var(--theme-card-border); color: var(--theme-text-primary);">

    @if($header)
        <div class="px-6 py-4 border-b"
             style="border-color: var(--theme-border-primary); background-color: var(--theme-bg-secondary);">
            {{ $header }}
        </div>
    @endif

    @if($body)
        <div class="p-6">
            {{ $slot }}
        </div>
    @endif

    @if($footer)
        <div class="px-6 py-4 border-t"
             style="border-color: var(--theme-border-primary); background-color: var(--theme-bg-secondary);">
            {{ $footer }}
        </div>
    @endif
</div>