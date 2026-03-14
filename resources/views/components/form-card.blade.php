@props(['title', 'action' => null, 'method' => 'POST', 'id' => null])

<div class="theme-card rounded-lg shadow p-6">
    @if($title)
        <h2 class="text-xl font-semibold theme-text-primary mb-6">{{ $title }}</h2>
    @endif

    <form 
        @if($action) action="{{ $action }}" @endif
        method="{{ $method }}"
        @if($id) id="{{ $id }}" @endif
        {{ $attributes->except(['title', 'action', 'method', 'id']) }}
        class="space-y-6"
    >
        @csrf
        {{ $slot }}
    </form>
</div>
