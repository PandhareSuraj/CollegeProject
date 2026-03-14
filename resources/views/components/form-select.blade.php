@props([
    'name' => '',
    'label' => '',
    'options' => [],
    'value' => '',
    'placeholder' => 'Select an option',
    'required' => false,
    'disabled' => false,
    'multiple' => false,
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
    
    <select 
        name="{{ $name }}"
        id="{{ $name }}"
        {{ $attributes->merge([
            'class' => 'w-full px-3 py-2 border rounded-lg theme-text-primary bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 ' . 
                       ($hasError ? 'border-red-500 dark:border-red-500' : 'border-gray-300 dark:border-gray-600'),
        ]) }}
        @if($required) required @endif
        @if($disabled) disabled @endif
        @if($multiple) multiple @endif
    >
        @if($placeholder && !$multiple)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if($slot->isNotEmpty())
            {{ $slot }}
        @else
            @forelse($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" {{ old($name, $value) == $optionValue ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @empty
            @endforelse
        @endif
    </select>

    @if($hasError)
        <p class="mt-1 text-sm text-red-600">{{ $errorMessage }}</p>
    @elseif($help)
        <p class="mt-1 text-xs theme-text-secondary">{{ $help }}</p>
    @endif
</div>
