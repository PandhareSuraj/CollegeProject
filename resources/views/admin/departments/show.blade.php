@extends('layouts.app')

@section('title', 'View Department')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h1><i class="fas fa-building"></i> {{ $department->name }}</h1>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('admin.departments.edit', $department->id) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Edit
        </a>
        <a href="{{ route('admin.departments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Department Details</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label><strong>Department Name:</strong></label>
                    <p>{{ $department->name }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Description:</strong></label>
                    <p>{{ $department->description ?? 'N/A' }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Created At:</strong></label>
                    <p>{{ $department->created_at->format('M d, Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Statistics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label><strong>Total Teachers:</strong></label>
                    <p style="font-size: 1.5rem; color: #3498db;">{{ $department->teachers->count() }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Total HODs:</strong></label>
                    <p style="font-size: 1.5rem; color: #2ecc71;">{{ $department->hods->count() }}</p>
                </div>

                <div class="mb-3">
                    <label><strong>Total Requests:</strong></label>
                    <p style="font-size: 1.5rem; color: #27ae60;">{{ $department->requests->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Department Users -->
<div class="card mt-3">
    <div class="card-header">
        <h5 class="mb-0"><i class="fas fa-users"></i> Department Staff</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $allUsers = collect();
                    foreach ($department->teachers as $teacher) {
                        $allUsers->push($teacher);
                    }
                    foreach ($department->hods as $hod) {
                        $allUsers->push($hod);
                    }
                @endphp
                @forelse($allUsers as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">No staff in this department.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
