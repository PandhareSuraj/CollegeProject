@extends('layouts.app')

@section('title', 'Edit Department')

@section('content')
<x-theme-container class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Edit Department" icon="pencil" />

    <div class="theme-card p-6">
        <form action="{{ route('admin.departments.update', $department->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <x-form-input 
                name="name"
                label="Department Name"
                required
                :value="old('name', $department->name)"
                placeholder="Enter department name"
            />

            <x-form-textarea 
                name="description"
                label="Description"
                rows="4"
                placeholder="Enter department description"
            >{{ old('description', $department->description) }}</x-form-textarea>

            <div class="flex gap-3 pt-4">
                <x-form-button type="submit" variant="primary">
                    Update Department
                </x-form-button>
                <x-action-button 
                    href="{{ route('admin.departments.index') }}"
                    type="secondary"
                >
                    Cancel
                </x-action-button>
            </div>
        </form>
    </div>
</x-theme-container>
@endsection
