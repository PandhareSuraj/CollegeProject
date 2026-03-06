@extends('layouts.app')

@section('title','Add College')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300 mb-6">Add College</h1>
    
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.colleges.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Sanstha Dropdown -->
            <div>
                <label for="sanstha_id" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Sanstha</label>
                <select id="sanstha_id" name="sanstha_id" required 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('sanstha_id') border-red-500 @enderror">
                    <option value="">-- Select Sanstha --</option>
                    @foreach(App\Models\Sanstha::all() as $s)
                        <option value="{{ $s->id }}" {{ old('sanstha_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                    @endforeach
                </select>
                @error('sanstha_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Name</label>
                <input type="text" id="name" name="name" required 
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('name') border-red-500 @enderror"
                       value="{{ old('name') }}" placeholder="Enter college name">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Address Field -->
            <div>
                <label for="address" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Address</label>
                <textarea id="address" name="address" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600"
                          placeholder="Enter college address">{{ old('address') }}</textarea>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                    Save
                </button>
                <a href="{{ route('admin.colleges.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
