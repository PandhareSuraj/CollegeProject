@props([
    'columns' => [],
])

<thead class="theme-bg-secondary border-b theme-border-primary">
    <tr>
        @forelse($columns as $column)
            <th class="px-6 py-3 text-left font-semibold theme-text-primary">
                {{ $column }}
            </th>
        @empty
            {{ $slot }}
        @endforelse
    </tr>
</thead>
