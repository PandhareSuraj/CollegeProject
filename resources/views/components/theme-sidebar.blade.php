@props([
    'width' => 'w-64',
])

<aside class="theme-sidebar {{ $width }} px-0 py-6">
    {{ $slot }}
</aside>