@extends('layouts.app')

@section('title', 'Department Management')

@section('content')
<x-theme-container class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Department Management" icon="building-office">
        <p class="text-sm theme-text-secondary mt-1">Manage all departments in the system</p>
    </x-page-header>

    <!-- Search and Filter Card -->
    <div class="theme-card mb-6">
        <div class="p-6">
            <form method="GET" action="{{ route('admin.college-section.department') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium theme-text-primary mb-2">Sanstha</label>
                        <select name="sanstha_id" class="w-full border theme-border-primary rounded-lg px-4 py-2 text-sm theme-bg-primary theme-text-primary focus:outline-none focus:ring-2 focus:ring-blue-600">
                            <option value="">-- All Sansthas --</option>
                            @foreach($sansthas as $sanstha)
                                <option value="{{ $sanstha->id }}" @if(request('sanstha_id') == $sanstha->id) selected @endif>
                                    {{ $sanstha->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium theme-text-primary mb-2">College</label>
                        <select name="college_id" class="w-full border theme-border-primary rounded-lg px-4 py-2 text-sm theme-bg-primary theme-text-primary focus:outline-none focus:ring-2 focus:ring-blue-600" id="collegeSelect">
                            <option value="">-- All Colleges --</option>
                            @if(request('sanstha_id'))
                                @foreach($sansthas as $sanstha)
                                    @if($sanstha->id == request('sanstha_id'))
                                        @foreach($sanstha->colleges as $college)
                                            <option value="{{ $college->id }}" @if(request('college_id') == $college->id) selected @endif>
                                                {{ $college->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @else
                                @foreach($sansthas as $sanstha)
                                    @foreach($sanstha->colleges as $college)
                                        <option value="{{ $college->id }}" @if(request('college_id') == $college->id) selected @endif>
                                            {{ $college->name }} ({{ $sanstha->name }})
                                        </option>
                                    @endforeach
                                @endforeach
                            @endif
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium theme-text-primary mb-2">Search Department</label>
                        <input type="search" name="search" class="w-full border theme-border-primary rounded-lg px-4 py-2 text-sm theme-bg-primary theme-text-primary focus:outline-none focus:ring-2 focus:ring-blue-600" placeholder="Search by name..." value="{{ request('search') }}">
                    </div>
                </div>
                
                <div class="flex gap-2">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">Filter</button>
                    <a href="{{ route('admin.college-section.department') }}" class="px-4 py-2 theme-bg-secondary theme-text-primary rounded-lg hover:theme-bg-secondary transition font-medium">Clear</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Table Card -->
    <div class="theme-card overflow-x-auto">
        <x-data-table>
            <x-table-header :columns="['#', 'Name', 'College']" />
            <tbody>
                @forelse($departments as $d)
                    <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                        <td class="px-6 py-4 font-semibold theme-text-primary">{{ $d->id }}</td>
                        <td class="px-6 py-4 theme-text-primary">{{ $d->name }}</td>
                        <td class="px-6 py-4 theme-text-secondary">{{ optional($d->college)->name ?? '-' }}</td>
                    </tr>
                @empty
                    <x-empty-state title="No departments found" colspan="3" />
                @endforelse
            </tbody>
        </x-data-table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $departments->links() }}
    </div>
</x-theme-container>
@endsection
