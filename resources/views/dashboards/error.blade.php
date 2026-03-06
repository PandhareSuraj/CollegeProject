@extends('layouts.app')

@section('title', 'Dashboard Error')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 mt-10">
    <div class="max-w-2xl mx-auto">
        <div class="bg-red-50 border-l-4 border-red-500 p-8 rounded-lg">
            <h1 class="text-3xl font-bold text-red-900 mb-2 flex items-center gap-2">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                Dashboard Error!
            </h1>
            <p class="text-red-800 mt-3 mb-4">Unable to determine your role. Please check your account details.</p>
            
            <hr class="border-red-300 my-6">
            
            <h3 class="text-lg font-semibold text-red-900 mb-4">Debug Information:</h3>
            <div class="space-y-2 text-sm text-red-800 bg-white p-4 rounded border border-red-200">
                <div class="flex justify-between">
                    <span class="font-semibold">Current Role Detected:</span>
                    <span>{{ $currentRole }}</span>
                </div>
                <div class="flex justify-between border-t border-red-200 pt-2">
                    <span class="font-semibold">User ID:</span>
                    <span>{{ $user->id }}</span>
                </div>
                <div class="flex justify-between border-t border-red-200 pt-2">
                    <span class="font-semibold">User Name:</span>
                    <span>{{ $user->name }}</span>
                </div>
                <div class="flex justify-between border-t border-red-200 pt-2">
                    <span class="font-semibold">User Email:</span>
                    <span>{{ $user->email }}</span>
                </div>
                <div class="flex justify-between border-t border-red-200 pt-2">
                    <span class="font-semibold">User Model Class:</span>
                    <span class="font-mono">{{ get_class($user) }}</span>
                </div>
                <div class="flex justify-between border-t border-red-200 pt-2">
                    <span class="font-semibold">User Table:</span>
                    <span class="font-mono">{{ $user->getTable() }}</span>
                </div>
                <div class="flex justify-between border-t border-red-200 pt-2">
                    <span class="font-semibold">Role Attribute Method:</span>
                    <span>
                        @if(method_exists($user, 'getRoleAttribute'))
                            <span class="text-green-600">✓ Exists</span>
                        @else
                            <span class="text-red-600">✗ Missing</span>
                        @endif
                    </span>
                </div>
            </div>
            
            <hr class="border-red-300 my-6">
            
            <div class="flex gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2 px-6 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                    </svg>
                    Go to Home
                </a>
                <a href="{{ route('logout') }}" class="flex items-center gap-2 px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
        .dashboard-error-wrapper .alert {
            padding: 1rem;
        }
    }
</style>
@endpush
