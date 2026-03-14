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
    <x-page-header title="Users Management">
        <x-action-button 
            href="{{ route('admin.users.create') }}"
            type="primary"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add User
        </x-action-button>
    </x-page-header>

    <x-data-table>
        <x-table-header :columns="['Name', 'Email', 'Role', 'Department', 'Joined', 'Actions']" />
        <tbody>
            @forelse($users as $user)
                <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                    <td class="px-6 py-4 theme-text-primary font-medium">{{ $user->name }}</td>
                    <td class="px-6 py-4 theme-text-secondary">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 theme-text-secondary">
                        {{ isset($user->department) && $user->department ? $user->department->name : 'N/A' }}
                    </td>
                    <td class="px-6 py-4 theme-text-secondary">{{ $user->created_at->format('M d, Y') }}</td>
                    <x-table-row-actions 
                        :view="route('admin.users.show', $user->id)"
                        :edit="route('admin.users.edit', $user->id)"
                        :deleteRoute="route('admin.users.destroy', $user->id)"
                    />
                </tr>
            @empty
                <x-empty-state 
                    title="No users found"
                    message="There are no users in the system yet."
                    action="{{ route('admin.users.create') }}"
                    actionLabel="Create User"
                    colspan="6"
                />
            @endforelse
        </tbody>
    </x-data-table>

    <!-- Pagination -->
    <div class="flex justify-center mt-4">
        {{ $users->links() }}
    </div>
</x-theme-container>

@endsection
