@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-purple-600 to-purple-800 text-white py-8 px-6 text-center">
        <h4 class="text-2xl font-bold mb-2">
            <svg class="w-6 h-6 inline mr-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M11 7h2v8h-2zm5-2.5A3.5 3.5 0 0 1 19.5 8 3.5 3.5 0 0 1 16 11.5 3.5 3.5 0 0 1 12.5 8 3.5 3.5 0 0 1 16 4.5zm0 10A5.5 5.5 0 0 1 21 20v1h-10v-1a5.5 5.5 0 0 1 5-5.5z"/>
            </svg>
            Login
        </h4>
        <p class="text-sm text-purple-100">Campus Store Management System</p>
    </div>

    <div class="p-6">
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <strong class="text-red-800">Login Failed</strong>
                    <div class="text-red-700 text-sm mt-1">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <p class="text-red-800 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('auth.login') }}" class="space-y-4">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                    </svg>
                    <input type="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('email') border-red-500 @enderror" 
                           id="email" name="email" value="{{ old('email') }}" 
                           placeholder="Enter your email" required autofocus>
                </div>
                @error('email')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password Input -->
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                    </svg>
                    <input type="password" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-600 focus:border-transparent @error('password') border-red-500 @enderror" 
                           id="password" name="password" placeholder="Enter your password" required>
                </div>
                @error('password')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <input type="checkbox" class="w-4 h-4 text-purple-600 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-600" id="remember" name="remember">
                <label class="ml-2 text-sm text-gray-700" for="remember">Remember me</label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                </svg>
                Login
            </button>
        </form>

        <div class="my-4 border-t border-gray-300 dark:border-gray-600"></div>

        <!-- Additional Links -->
        <div class="text-center space-y-2 text-sm">
            <p class="mb-2">
                <a href="{{ route('auth.role-selection') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                    Login by Role →
                </a>
            </p>
            <p>
                <a href="{{ route('home') }}" class="text-gray-600 hover:text-gray-700">Back to Home</a>
            </p>
        </div>
    </div>
</div>
@endsection
