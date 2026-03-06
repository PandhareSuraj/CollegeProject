@extends('layouts.app')

@section('title','Add Vendor')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-300">Add Vendor</h1>
        <a href="{{ route('admin.vendors.index') }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 font-medium">Back to Vendors</a>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
        <form method="POST" action="{{ route('admin.vendors.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Name</label>
                <input type="text" id="name" name="name" required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('name') border-red-500 @enderror"
                       value="{{ old('name') }}" placeholder="Vendor name">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Company Name</label>
                <input type="text" id="company_name" name="company_name"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('company_name') border-red-500 @enderror"
                       value="{{ old('company_name') }}" placeholder="Company name">
                @error('company_name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Phone and Email Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="phone" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Phone</label>
                    <input type="text" id="phone" name="phone"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('phone') border-red-500 @enderror"
                           value="{{ old('phone') }}" placeholder="+91-999999999">
                    @error('phone')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Email</label>
                    <input type="email" id="email" name="email"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('email') border-red-500 @enderror"
                           value="{{ old('email') }}" placeholder="vendor@example.com">
                    @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <!-- Address -->
            <div>
                <label for="address" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">Address</label>
                <textarea id="address" name="address" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('address') border-red-500 @enderror"
                          placeholder="Full address">{{ old('address') }}</textarea>
                @error('address')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- GST Number -->
            <div>
                <label for="gst_number" class="block text-sm font-semibold text-gray-900 dark:text-gray-300 mb-2">GST Number</label>
                <input type="text" id="gst_number" name="gst_number"
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-600 @error('gst_number') border-red-500 @enderror"
                       value="{{ old('gst_number') }}" placeholder="XX AABBBB0000A1Z5">
                @error('gst_number')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <!-- Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                    Save Vendor
                </button>
                <a href="{{ route('admin.vendors.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
