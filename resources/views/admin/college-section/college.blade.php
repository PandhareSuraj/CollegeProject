@extends('layouts.app')

@section('title', 'College Management')

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="College Management" icon="building-office">
        <p class="text-sm theme-text-secondary mt-1">Manage all colleges in the system</p>
    </x-page-header>

    <!-- Search Card -->
    <div class="theme-card mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.college-section.college') }}" class="flex gap-2">
                <input type="search" name="search" class="flex-1 border theme-border-primary rounded-lg px-4 py-2 text-sm theme-bg-primary theme-text-primary focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search colleges..." value="{{ request('search') }}">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Search</button>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="theme-card overflow-x-auto">
        <x-data-table>
            <x-table-header :columns="['#', 'Name', 'Sanstha', 'Address']" />
            <tbody>
                @forelse($colleges as $c)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-4 font-semibold theme-text-primary">{{ $c->id }}</td>
                        <td class="px-6 py-4 theme-text-primary">{{ $c->name }}</td>
                        <td class="px-6 py-4 theme-text-secondary">{{ optional($c->sanstha)->name ?? '-' }}</td>
                        <td class="px-6 py-4 theme-text-secondary">{{ $c->address }}</td>
                    </tr>
                @empty
                    <x-empty-state title="No colleges found" colspan="4" />
                @endforelse
            </tbody>
        </x-data-table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $colleges->links() }}
    </div>
</x-theme-container>
@endsection

