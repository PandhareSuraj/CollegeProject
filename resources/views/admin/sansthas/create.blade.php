@extends('layouts.app')

@section('title','Add Sanstha')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300 mb-6">Add Sanstha</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.sansthas.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Single Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">
                    Name (single) — or use 'Add multiple' below
                </label>
                <input type="text" id="name" name="name" 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('name') border-red-500 @enderror"
                       value="{{ old('name') }}">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Multi Add Toggle -->
            <div class="flex items-center gap-3">
                <input type="checkbox" id="multiAddToggle" value="1" name="multi_add" class="h-4 w-4 text-blue-600 rounded border-gray-300">
                <label for="multiAddToggle" class="text-sm font-medium text-gray-900 dark:text-gray-300 cursor-pointer">Add multiple (one per line)</label>
            </div>

            <!-- Multiple Names / Descriptions -->
            <div>
                <label for="multi_names" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Multiple Names / Descriptions (optional)</label>
                <textarea id="multi_names" name="multi_names" rows="6"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
                          placeholder="One per line. Optional description after a '|' character.&#10;Example: College A|Main campus"></textarea>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">If 'Add multiple' is checked, this textarea will be parsed and multiple Sansthas will be created.</p>
            </div>

            <!-- Description Field -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Description (single)</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
                          placeholder="Enter description">{{ old('description') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                    Save
                </button>
                <a href="{{ url()->previous() }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
