@props([
    'striped' => true,
    'hoverable' => true,
    'class' => '',
])

<div class="overflow-x-auto">
    <table @class([
        'w-full text-sm',
        $class,
    ])>
        {{ $slot }}
    </table>
</div>
