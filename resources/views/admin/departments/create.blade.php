@extends('layouts.app')

@section('title','Add Department')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300">Add Department</h1>
        <a href="{{ route('admin.departments.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">Back to list</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.departments.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- College / Sanstha Selection -->
            <div>
                <label class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">College / Sanstha</label>
                
                @if($colleges->isEmpty())
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                        <p class="text-sm text-amber-800">No colleges found. Please create a Sanstha/College first or enter a name below to create one.</p>
                    </div>
                    <div>
                        <label for="new_sanstha_name" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Create Sanstha (enter name)</label>
                        <input type="text" id="new_sanstha_name" name="new_sanstha_name" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('new_sanstha_name') border-red-500 @enderror"
                               value="{{ old('new_sanstha_name') }}" placeholder="Enter Sanstha/College name to create and link">
                        @error('new_sanstha_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">If you provide a name here the Sanstha will be created and the department will be linked to it automatically.</p>
                    </div>
                @else
                    <select id="college_id" name="college_id" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('college_id') border-red-500 @enderror">
                        <option value="">-- Select College (or enter a new name below) --</option>
                        @foreach($colleges as $c)
                            <option value="{{ $c->id }}" {{ old('college_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                    @error('college_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror

                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <label for="new_sanstha_name" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-2">Or create and link a new Sanstha</label>
                        <input type="text" id="new_sanstha_name" name="new_sanstha_name" 
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
                               value="{{ old('new_sanstha_name') }}" placeholder="Optional: create new Sanstha and link">
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">If you enter a name here it will create a new Sanstha and the department will be linked to it.</p>
                    </div>
                @endif
            </div>

            <!-- Department Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Name</label>
                <input type="text" id="name" name="name" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('name') border-red-500 @enderror"
                       value="{{ old('name') }}" placeholder="Enter department name">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Description (optional)</label>
                <textarea id="description" name="description" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
                          placeholder="Enter department description">{{ old('description') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                    Save Department
                </button>
                <a href="{{ route('admin.departments.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
