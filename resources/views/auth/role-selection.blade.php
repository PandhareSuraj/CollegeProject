<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" id="html-root">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Role Selection - Campus Store</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Default to dark mode if no preference is saved
        if (localStorage.getItem('theme') === 'light') {
            document.getElementById('html-root').classList.remove('dark');
        } else {
            document.getElementById('html-root').classList.add('dark');
        }
    </script>
</head>
<body class="bg-gradient-to-br from-blue-600 to-blue-900 dark:from-blue-950 dark:to-slate-950 min-h-screen text-gray-900 dark:text-gray-100 transition-colors duration-200">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-6xl">
            <!-- Dark overlay card -->
            <div class="bg-gray-900 dark:bg-gray-950 rounded-2xl shadow-2xl p-8 md:p-12">
                <!-- Header -->
                <div class="text-center mb-12">
                    <h1 class="text-4xl md:text-5xl font-bold text-white mb-3">Welcome to Campus Store</h1>
                    <p class="text-gray-300 text-lg">Select your role to continue</p>
                </div>

                <!-- Roles Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <!-- Teacher -->
                    <a href="{{ route('auth.role-login', 'teacher') }}" class="group block">
                        <div class="h-full bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 p-6 text-center border-2 border-blue-400">
                            <div class="mb-4 flex justify-center">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H4a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V7.414a2 2 0 00-.586-1.414l-6.336-6.336A2 2 0 0012.586 1.5H10.5z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Teacher</h3>
                            <p class="text-blue-100 text-sm">Create and track requests</p>
                        </div>
                    </a>

                    <!-- HOD -->
                    <a href="{{ route('auth.role-login', 'hod') }}" class="group block">
                        <div class="h-full bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 p-6 text-center border-2 border-green-400">
                            <div class="mb-4 flex justify-center">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">HOD</h3>
                            <p class="text-green-100 text-sm">Review & approve requests</p>
                        </div>
                    </a>

                    <!-- Principal -->
                    <a href="{{ route('auth.role-login', 'principal') }}" class="group block">
                        <div class="h-full bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 p-6 text-center border-2 border-cyan-400">
                            <div class="mb-4 flex justify-center">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.5 1.5H4a2 2 0 00-2 2v14a2 2 0 002 2h12a2 2 0 002-2V7.414a2 2 0 00-.586-1.414l-6.336-6.336A2 2 0 0012.586 1.5H10.5z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Principal</h3>
                            <p class="text-cyan-100 text-sm">Institutional approvals</p>
                        </div>
                    </a>

                    <!-- Trust Head -->
                    <a href="{{ route('auth.role-login', 'trust_head') }}" class="group block">
                        <div class="h-full bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 p-6 text-center border-2 border-amber-400">
                            <div class="mb-4 flex justify-center">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 3a1 1 0 011-1h6a1 1 0 011 1v2a1 1 0 11-2 0V4H8v2a1 1 0 01-2 0V3zm0 3a1 1 0 00-1 1v8a2 2 0 002 2h8a2 2 0 002-2V7a1 1 0 00-1-1h-1.277l-.555-1.666A1 1 0 0013 4H7a1 1 0 00-.939.555L5.277 6H4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Trust Head</h3>
                            <p class="text-amber-100 text-sm">Governance oversight</p>
                        </div>
                    </a>

                    <!-- Provider -->
                    <a href="{{ route('auth.role-login', 'provider') }}" class="group block">
                        <div class="h-full bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 p-6 text-center border-2 border-red-400">
                            <div class="mb-4 flex justify-center">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 6H6.28l-.31-1.243A1 1 0 005 4H3z"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Provider</h3>
                            <p class="text-red-100 text-sm">Manage supply orders</p>
                        </div>
                    </a>

                    <!-- Admin -->
                    <a href="{{ route('auth.role-login', 'admin') }}" class="group block">
                        <div class="h-full bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg hover:shadow-2xl transition-all transform hover:scale-105 p-6 text-center border-2 border-purple-400">
                            <div class="mb-4 flex justify-center">
                                <svg class="w-14 h-14 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-2.126 3.066 3.066 0 00-3.58 3.83c.075.245.166.48.259.707l-6.571 3.285a2.64 2.64 0 02-.967 4.167c.05.643.304 1.254.714 1.747m6.171 8.792a26.992 26.992 0 007.477-2.796c2.016 1.007 4.26 1.405 6.514 1.207.031 7.36.031 7.36-1.234 7.36-8.414 0-15.42 4.716-19.332 11.466.159.186.319.37.477.556z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-white mb-2">Administrator</h3>
                            <p class="text-purple-100 text-sm">System administration</p>
                        </div>
                    </a>
                </div>

                <!-- Divider -->
                <div class="border-t border-gray-700 my-8"></div>

                <!-- Footer -->
                <div class="text-center">
                    <p class="text-gray-400 mb-4">Don't have an account?</p>
                    <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-blue-600 hover:bg-blue-700 dark:hover:bg-blue-800 text-white font-semibold rounded-lg transition-all transform hover:scale-105 mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M8 16A8 8 0 118 0a8 8 0 010 16zm3.5-9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        </svg>
                        Create New Account
                    </a>
                    <div>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition">← Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Theme toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const useDark = saved ? saved === 'dark' : prefersDark;
            document.getElementById('html-root').classList.toggle('dark', useDark);
        });
    </script>
</body>
</html>