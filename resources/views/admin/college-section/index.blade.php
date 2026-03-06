@extends('layouts.app')

@section('title', 'College Section')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">College Section</h1>
        <small class="text-muted">Manage Sanstha / College / Department / Department Users</small>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="nav nav-tabs" id="collegeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="sanstha-tab" data-bs-toggle="tab" data-bs-target="#sanstha" type="button" role="tab" aria-controls="sanstha" aria-selected="true">Sanstha</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="college-tab" data-bs-toggle="tab" data-bs-target="#college" type="button" role="tab" aria-controls="college" aria-selected="false">College</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="department-tab" data-bs-toggle="tab" data-bs-target="#department" type="button" role="tab" aria-controls="department" aria-selected="false">Department</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dept-users-tab" data-bs-toggle="tab" data-bs-target="#dept-users" type="button" role="tab" aria-controls="dept-users" aria-selected="false">Department Users</button>
                </li>
            </ul>

            <div class="tab-content mt-3" id="collegeTabsContent">
                <!-- Sanstha Tab -->
                <div class="tab-pane fade show active" id="sanstha" role="tabpanel" aria-labelledby="sanstha-tab">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <a href="#" class="btn btn-primary" onclick="return false;">Add Sanstha</a>
                        </div>
                        <div class="input-group w-50">
                            <input type="search" class="form-control" placeholder="Search sansthas..." aria-label="Search sansthas">
                            <button class="btn btn-outline-secondary">Search</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th style="width:180px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sansthas as $s)
                                    <tr>
                                        <td>{{ $s->id }}</td>
                                        <td>{{ $s->name }}</td>
                                        <td>{{ Illuminate\Support\Str::limit($s->description, 80) }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <form method="POST" action="#" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this sanstha?')">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">No sansthas found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $sansthas->links() }}
                    </div>
                </div>

                <!-- College Tab -->
                <div class="tab-pane fade" id="college" role="tabpanel" aria-labelledby="college-tab">
                    <div class="d-flex justify-content-between mb-3">
                        <a href="#" class="btn btn-primary" onclick="return false;">Add College</a>
                        <div class="input-group w-50">
                            <input type="search" class="form-control" placeholder="Search colleges..." aria-label="Search colleges">
                            <button class="btn btn-outline-secondary">Search</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th>Name</th>
                                    <th>Sanstha</th>
                                    <th>Address</th>
                                    <th style="width:180px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($colleges as $c)
                                    <tr>
                                        <td>{{ $c->id }}</td>
                                        <td>{{ $c->name }}</td>
                                        <td>{{ $c->sanstha->name ?? '-' }}</td>
                                        <td>{{ $c->address }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No colleges found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $colleges->links() }}
                    </div>
                </div>

                <!-- Department Tab -->
                <div class="tab-pane fade" id="department" role="tabpanel" aria-labelledby="department-tab">
                    <div class="d-flex justify-content-between mb-3">
                        <a href="{{ route('admin.departments.create') }}" class="btn btn-primary">Add Department</a>
                        <div class="input-group w-50">
                            <input type="search" class="form-control" placeholder="Search departments..." aria-label="Search departments">
                            <button class="btn btn-outline-secondary">Search</button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th>Name</th>
                                    <th>College</th>
                                    <th style="width:180px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departments as $d)
                                    <tr>
                                        <td>{{ $d->id }}</td>
                                        <td>{{ $d->name }}</td>
                                        <td>{{ $d->college->name ?? '-' }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">No departments found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $departments->links() }}
                    </div>
                </div>

                <!-- Department Users Tab -->
                <div class="tab-pane fade" id="dept-users" role="tabpanel" aria-labelledby="dept-users-tab">
                    <form method="GET" class="row gy-2 gx-2 align-items-center mb-3">
                        <div class="col-md-6">
                            <select class="form-select" name="department_id">
                                <option value="">Select Department</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->id }}" @if(request('department_id') == $d->id) selected @endif>{{ $d->name }} ({{ $d->college->name ?? '' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary">Load Users</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:60px">#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th style="width:140px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($departmentUsers as $u)
                                    <tr>
                                        <td>{{ $u->id }}</td>
                                        <td>{{ $u->name }}</td>
                                        <td>{{ $u->email ?? '-' }}</td>
                                        <td>{{ $u->role ?? '-' }}</td>
                                        <td>
                                            <a href="#" class="btn btn-sm btn-outline-secondary">View</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">No users found for this department.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preserve active tab in hash and on page load
    (function() {
        const triggerTabList = [].slice.call(document.querySelectorAll('#collegeTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            const tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                const target = event.currentTarget.getAttribute('data-bs-target')
                history.replaceState(null, null, '#'+target.replace('#',''))
            })
        })

        // On load, activate tab from hash
        const hash = location.hash
        if (hash) {
            const el = document.querySelector('#collegeTabs button[data-bs-target="'+hash+'"]')
            if (el) {
                new bootstrap.Tab(el).show()
            }
        }
    })();
</script>
@endpush

@endsection
