@extends('layouts.app')

@section('title', 'College Section')

@section('content')
<x-theme-container class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header 
        title="College Section" 
        icon="building-office"
    >
        <p class="text-sm theme-text-secondary mt-1">Manage Sanstha / College / Department / Department Users</p>
    </x-page-header>

    <div class="theme-card">
        <!-- Tab Buttons -->
        <div class="flex border-b theme-border-primary">
            <button data-tab="sanstha" class="tab-button active px-6 py-3 font-medium theme-text-primary border-b-2 border-blue-600">
                Sanstha
            </button>
            <button data-tab="college" class="tab-button px-6 py-3 font-medium theme-text-secondary border-b-2 border-transparent hover:theme-text-primary">
                College
            </button>
            <button data-tab="department" class="tab-button px-6 py-3 font-medium theme-text-secondary border-b-2 border-transparent hover:theme-text-primary">
                Department
            </button>
            <button data-tab="dept-users" class="tab-button px-6 py-3 font-medium theme-text-secondary border-b-2 border-transparent hover:theme-text-primary">
                Department Users
            </button>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- Sanstha Tab -->
            <div id="sanstha-panel" class="tab-panel active">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <x-action-button href="#" type="primary" onclick="return false;">
                            Add Sanstha
                        </x-action-button>
                    </div>
                    <div class="flex gap-2">
                        <input type="search" class="px-4 py-2 border theme-border-primary rounded-lg theme-bg-primary theme-text-primary" placeholder="Search sansthas..." aria-label="Search sansthas">
                        <button class="px-4 py-2 border theme-border-primary text-theme-text-primary rounded-lg hover:theme-bg-secondary transition">Search</button>
                    </div>
                </div>

                <x-data-table>
                    <x-table-header :columns="['#', 'Name', 'Description', 'Actions']" />
                    <tbody>
                        @forelse($sansthas as $s)
                            <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                                <td class="px-6 py-3 font-semibold theme-text-primary">{{ $s->id }}</td>
                                <td class="px-6 py-3 theme-text-primary">{{ $s->name }}</td>
                                <td class="px-6 py-3 theme-text-secondary">{{ Illuminate\Support\Str::limit($s->description, 80) }}</td>
                                <td class="px-6 py-3 flex gap-2">
                                    <x-action-button href="#" type="secondary" class="w-20 text-center">Edit</x-action-button>
                                    <form method="POST" action="#" class="inline" onsubmit="return confirm('Delete this sanstha?')">
                                        @csrf
                                        <button type="submit" class="px-3 py-1 text-sm bg-red-100 text-red-600 rounded hover:bg-red-200 transition">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <x-empty-state title="No sansthas found" colspan="4" />
                        @endforelse
                    </tbody>
                </x-data-table>

                <div class="mt-4">
                    {{ $sansthas->links() }}
                </div>
            </div>

            <!-- College Tab -->
            <div id="college-panel" class="tab-panel hidden">
                <div class="flex justify-between items-center mb-4">
                    <x-action-button href="#" type="primary" onclick="return false;">
                        Add College
                    </x-action-button>
                    <div class="flex gap-2">
                        <input type="search" class="px-4 py-2 border theme-border-primary rounded-lg theme-bg-primary theme-text-primary" placeholder="Search colleges..." aria-label="Search colleges">
                        <button class="px-4 py-2 border theme-border-primary text-theme-text-primary rounded-lg hover:theme-bg-secondary transition">Search</button>
                    </div>
                </div>

                <x-data-table>
                    <x-table-header :columns="['#', 'Name', 'Sanstha', 'Address', 'Actions']" />
                    <tbody>
                        @forelse($colleges as $c)
                            <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                                <td class="px-6 py-3 font-semibold theme-text-primary">{{ $c->id }}</td>
                                <td class="px-6 py-3 theme-text-primary">{{ $c->name }}</td>
                                <td class="px-6 py-3 theme-text-secondary">{{ optional($c->sanstha)->name ?? '-' }}</td>
                                <td class="px-6 py-3 theme-text-secondary">{{ $c->address }}</td>
                                <td class="px-6 py-3 flex gap-2">
                                    <x-action-button href="#" type="secondary" class="w-20 text-center">Edit</x-action-button>
                                    <x-action-button href="#" type="danger" class="w-24 text-center">Delete</x-action-button>
                                </td>
                            </tr>
                        @empty
                            <x-empty-state title="No colleges found" colspan="5" />
                        @endforelse
                    </tbody>
                </x-data-table>

                <div class="mt-4">
                    {{ $colleges->links() }}
                </div>
            </div>

            <!-- Department Tab -->
            <div id="department-panel" class="tab-panel hidden">
                <div class="flex justify-between items-center mb-4">
                    <x-action-button href="{{ route('admin.departments.create') }}" type="primary">
                        Add Department
                    </x-action-button>
                    <div class="flex gap-2">
                        <input type="search" class="px-4 py-2 border theme-border-primary rounded-lg theme-bg-primary theme-text-primary" placeholder="Search departments..." aria-label="Search departments">
                        <button class="px-4 py-2 border theme-border-primary text-theme-text-primary rounded-lg hover:theme-bg-secondary transition">Search</button>
                    </div>
                </div>

                <x-data-table>
                    <x-table-header :columns="['#', 'Name', 'College', 'Actions']" />
                    <tbody>
                        @forelse($departments as $d)
                            <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                                <td class="px-6 py-3 font-semibold theme-text-primary">{{ $d->id }}</td>
                                <td class="px-6 py-3 theme-text-primary">{{ $d->name }}</td>
                                <td class="px-6 py-3 theme-text-secondary">{{ optional($d->college)->name ?? '-' }}</td>
                                <td class="px-6 py-3 flex gap-2">
                                    <x-action-button href="#" type="secondary" class="w-20 text-center">Edit</x-action-button>
                                    <x-action-button href="#" type="danger" class="w-24 text-center">Delete</x-action-button>
                                </td>
                            </tr>
                        @empty
                            <x-empty-state title="No departments found" colspan="4" />
                        @endforelse
                    </tbody>
                </x-data-table>

                <div class="mt-4">
                    {{ $departments->links() }}
                </div>
            </div>

            <!-- Department Users Tab -->
            <div id="dept-users-panel" class="tab-panel hidden">
                <form method="GET" class="flex gap-2 items-center mb-4">
                    <select class="flex-1 px-4 py-2 border theme-border-primary rounded-lg theme-bg-primary theme-text-primary" name="department_id">
                        <option value="">Select Department</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}" @if(request('department_id') == $d->id) selected @endif>{{ $d->name }} ({{ optional($d->college)->name ?? '' }})</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        Load Users
                    </button>
                </form>

                <x-data-table>
                    <x-table-header :columns="['#', 'Name', 'Email', 'Role', 'Actions']" />
                    <tbody>
                        @forelse($departmentUsers as $u)
                            <tr class="border-b theme-border-primary hover:theme-bg-secondary transition">
                                <td class="px-6 py-3 font-semibold theme-text-primary">{{ $u->id }}</td>
                                <td class="px-6 py-3 theme-text-primary">{{ $u->name }}</td>
                                <td class="px-6 py-3 theme-text-secondary">{{ $u->email ?? '-' }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                        {{ ucfirst(str_replace('_', ' ', $u->role ?? 'unknown')) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    <x-action-button href="#" type="secondary" class="w-16 text-center text-sm">View</x-action-button>
                                </td>
                            </tr>
                        @empty
                            <x-empty-state title="No users found for this department" colspan="5" />
                        @endforelse
                    </tbody>
                </x-data-table>
            </div>
        </div>
    </div>
</x-theme-container>

@push('scripts')
<script>
    document.querySelectorAll('[data-tab]').forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Hide all panels
            document.querySelectorAll('.tab-panel').forEach(panel => {
                panel.classList.add('hidden');
            });
            
            // Show selected panel
            document.getElementById(tabName + '-panel').classList.remove('hidden');
            
            // Update button styles
            document.querySelectorAll('[data-tab]').forEach(btn => {
                btn.classList.remove('theme-text-primary', 'border-blue-600');
                btn.classList.add('theme-text-secondary', 'border-transparent');
            });
            
            this.classList.remove('theme-text-secondary', 'border-transparent');
            this.classList.add('theme-text-primary', 'border-blue-600');
            
            // Update URL hash
            history.replaceState(null, null, '#' + tabName);
        });
    });

    // Restore tab from hash on load
    const hash = location.hash.replace('#', '');
    if (hash) {
        const button = document.querySelector(`[data-tab="${hash}"]`);
        if (button) {
            button.click();
        }
    }
</script>
@endpush

@endsection
