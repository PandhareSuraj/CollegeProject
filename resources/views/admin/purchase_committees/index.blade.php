@extends('layouts.app')

@section('title','Purchase Committees')

@section('navbar-title')
<div style="display: flex; align-items: center; gap: 12px;">
    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v1a1 1 0 01-1 1H1v-2a6 6 0 0112 0v1a1 1 0 001 1h3a1 1 0 001-1v-1zM6 16a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
    <h2 class="navbar-title">Purchase Committees</h2>
</div>
@endsection

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold theme-text-primary">Purchase Committees</h1>
        <a href="{{ route('admin.purchase-committees.create') }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition font-medium">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Committee
        </a>
    </div>

    <!-- Committees Table -->
    <div class="theme-card rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="theme-bg-secondary border-b theme-border-primary">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary w-12">#</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Name</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Description</th>
                    <th class="px-6 py-3 text-left font-semibold theme-text-primary">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($committees as $c)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-4 theme-text-primary">{{ $c->id }}</td>
                        <td class="px-6 py-4 theme-text-primary font-medium">{{ $c->name }}</td>
                        <td class="px-6 py-4 theme-text-secondary">{{ $c->description }}</td>
                        <td class="px-6 py-4 flex gap-2">
                            <a href="{{ route('admin.purchase-committees.edit', $c->id) }}" class="px-3 py-2 text-sm border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
                                Edit
                            </a>
                            <form action="{{ route('admin.purchase-committees.destroy', $c->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 text-sm bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition font-medium" onclick="return confirm('Delete this committee?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $committees->links() }}
    </div>
</div>
@endsection
