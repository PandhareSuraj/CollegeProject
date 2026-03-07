@extends('layouts.app')

@section('title', 'Users Management')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="currentColor" viewBox="0 0 20 20">
        <path d="M12 12a2 2 0 100-4 2 2 0 000 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
    </svg>
    <h2 class="navbar-title">Users</h2>
</div>
@endsection

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold theme-text-primary flex items-center gap-3">
                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0zM9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM14.5 8a2.5 2.5 0 100-5 2.5 2.5 0 000 5z"/>
                    <path d="M16.82 16a4 4 0 01-8.04 0c0-.93.402-1.79 1.068-2.567a6 6 0 016.905 0c.666.776 1.068 1.636 1.068 2.567zM2.5 10a3.5 3.5 0 100-7 3.5 3.5 0 000 7z"/>
                </svg>
                Users Management
            </h1>
        </div>
        <a href="{{ route('admin.users.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition text-lg font-medium">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add User
        </a>
    </div>

    <!-- Users Table -->
    <div class="theme-card overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="theme-bg-secondary border-b theme-border-primary">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Name</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Email</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Role</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Department</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Joined</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-4 theme-text-primary font-medium">{{ $user->name }}</td>
                        <td class="px-6 py-4 theme-text-secondary">{{ $user->email }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                {{ ucfirst($user->role) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 theme-text-secondary">
                            {{ isset($user->department) && $user->department ? $user->department->name : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 theme-text-secondary">{{ $user->created_at->format('M d, Y') }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 hover:bg-blue-200 transition" title="View">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                    <path fill-rule="evenodd" d="M0 10s3-5.5 10-5.5S20 10 20 10s-3 5.5-10 5.5S0 10 0 10zm10-8c-4.41 0-8 2.908-8 6.5S5.59 18 10 18s8-2.908 8-6.5S14.41 2 10 2z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 hover:bg-amber-200 transition" title="Edit">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-100 text-red-600 hover:bg-red-200 transition" title="Delete" onclick="return confirm('Are you sure?')">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center theme-text-tertiary">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $users->links() }}
    </div>
</x-theme-container>

@endsection
