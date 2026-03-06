@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300 mb-6 flex items-center gap-2">
        <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
        </svg>
        Edit Department
    </h1>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Department Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">
                    Department Name <span class="text-red-500">*</span>
                </label>
                <input type="text" id="name" name="name" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('name') border-red-500 @enderror"
                       value="{{ old('name', $department->name) }}" placeholder="Enter department name">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('description') border-red-500 @enderror"
                          placeholder="Enter department description">{{ old('description', $department->description) }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M5.5 13a3.5 3.5 0 01-.369-6.98 4 4 0 117.753-1 4.5 4.5 0 11-4.814 6.98z"/>
                    </svg>
                    Update Department
                </button>
                <a href="{{ route('admin.departments.index') }}" class="flex items-center gap-2 px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
