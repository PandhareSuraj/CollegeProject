@props([
    'title' => '',
    'sidebarWidth' => 'left-64',
])

<nav class="theme-navbar {{ $sidebarWidth }}">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            {{ $slot }}
        </div>
        @if($title)
            <h1 class="theme-text-primary text-lg font-semibold">{{ $title }}</h1>
        @endif
    </div>
</nav>