@props([
    'name' => '',
    'label' => '',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'help' => null,
    'error' => null,
])

@php
    $hasError = $error || $errors->has($name);
    $errorMessage = $error ?: $errors->first($name);
@endphp

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium theme-text-primary mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        {{ $attributes->merge([
            'class' => 'w-full px-3 py-2 border rounded-lg theme-text-primary bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 ' . 
                       ($hasError ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600'),
        ]) }}
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($readonly) readonly @endif
    />

    @if($hasError)
        <p class="mt-1 text-sm text-red-600">{{ $errorMessage }}</p>
    @elseif($help)
        <p class="mt-1 text-xs theme-text-secondary">{{ $help }}</p>
    @endif
</div>
