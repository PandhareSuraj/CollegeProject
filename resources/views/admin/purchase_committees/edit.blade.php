@extends('layouts.app')

@section('title','Edit Purchase Committee')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300">Edit Purchase Committee</h1>
        <a href="{{ route('admin.purchase-committees.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">Back</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.purchase-committees.update', $committee->id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Name</label>
                <input type="text" id="name" name="name" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('name') border-red-500 @enderror"
                       value="{{ old('name', $committee->name) }}" placeholder="Committee name">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Description</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('description') border-red-500 @enderror"
                          placeholder="Committee description">{{ old('description', $committee->description) }}</textarea>
                @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                    Update Committee
                </button>
                <a href="{{ route('admin.purchase-committees.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

