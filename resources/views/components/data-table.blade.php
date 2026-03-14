@props([
    'striped' => true,
    'hover' => true,
])

<div class="theme-card overflow-x-auto rounded-lg shadow">
    <table class="w-full text-sm">
        {{ $slot }}
    </table>
</div>
