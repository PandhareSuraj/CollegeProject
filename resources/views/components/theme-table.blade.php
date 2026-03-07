@props([
    'striped' => true,
    'hover' => true,
])

<div class="overflow-x-auto rounded-lg border"
     style="border-color: var(--theme-border-primary); background-color: var(--theme-card-bg);">
    <table class="theme-table min-w-full">
        {{ $slot }}
    </table>
</div>