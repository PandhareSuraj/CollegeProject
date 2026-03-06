@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center border-l-4 border-red-500">
        <div class="mb-6 flex justify-center">
            <svg class="w-20 h-20 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-red-500 mb-3">403 - Access Denied</h1>
        <p class="text-gray-600 mb-6 text-lg">
            You don't have permission to access this resource.
        </p>
        
        @if(auth()->check())
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6 text-left">
                <p class="mb-2">
                    <strong class="text-gray-900">Your Role:</strong>
                    <span class="inline-flex items-center px-3 py-1 ml-2 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                    </span>
                </p>
                @if(auth()->user()->department)
                    <p class="mb-2">
                        <strong class="text-gray-900">Department:</strong>
                        <span class="inline-flex items-center px-3 py-1 ml-2 rounded-full text-sm font-medium bg-gray-200 text-gray-800">
                            {{ auth()->user()->department->name }}
                        </span>
                    </p>
                @endif
            </div>
        @endif

        <div class="flex flex-col gap-3">
            <a href="{{ url()->previous() }}" class="px-6 py-3 border-2 border-red-500 text-red-500 font-semibold rounded-lg hover:bg-red-50 transition inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.707 9.293a1 1 0 010 1.414L5.414 13H17a1 1 0 110 2H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Go Back
            </a>
            <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-red-500 text-white font-semibold rounded-lg hover:bg-red-600 transition inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
                </svg>
                Go to Dashboard
            </a>
        </div>

        <hr class="my-6 border-gray-200">

        <p class="text-gray-500 text-sm">
            If you believe this is an error, please contact your administrator.
        </p>
    </div>
</div>
@endsection
