@props([
    'quantity',
    'label' => null,
])

<span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $quantity > 0 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' }}">
    @if($label)
        {{ $label }}: {{ $quantity }}
    @else
        {{ $quantity }} {{ Str::plural('unit', $quantity) }}
    @endif
</span>
