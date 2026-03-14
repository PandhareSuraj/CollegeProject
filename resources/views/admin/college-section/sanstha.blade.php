@extends('layouts.app')

@section('title', 'Sanstha Management')

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Sanstha Management" icon="building-office">
        <p class="text-sm theme-text-secondary mt-1">Manage all Sansthas in the system</p>
    </x-page-header>

    <!-- Card -->
    <div class="theme-card">
        <div class="p-6">
            <!-- Search Form -->
            <form method="GET" action="{{ route('admin.college-section.sanstha') }}" class="flex gap-3 mb-6">
                <input type="search" name="search" class="flex-1 px-4 py-2 border theme-border-primary rounded-lg theme-bg-primary theme-text-primary focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search sansthas..." value="{{ request('search') }}">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">Search</button>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto">
                <x-data-table>
                    <x-table-header :columns="['#', 'Name', 'Description']" />
                    <tbody>
                        @forelse($sansthas as $s)
                            <tr class="border-b theme-border-primary hover:theme-bg-secondary transition-colors">
                                <td class="px-6 py-3 text-sm font-semibold theme-text-primary">{{ $s->id }}</td>
                                <td class="px-6 py-3 text-sm theme-text-primary">{{ $s->name }}</td>
                                <td class="px-6 py-3 text-sm theme-text-secondary">{{ Illuminate\Support\Str::limit($s->description, 80) }}</td>
                            </tr>
                        @empty
                            <x-empty-state title="No sansthas found" colspan="3" />
                        @endforelse
                    </tbody>
                </x-data-table>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $sansthas->links() }}
            </div>
        </div>
    </div>
</x-theme-container>
@endsection
