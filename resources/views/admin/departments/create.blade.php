@extends('layouts.app')

@section('title','Add Department')

@section('content')
<x-theme-container class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Add Department" icon="plus" />

    <div class="theme-card p-6">
        <form method="POST" action="{{ route('admin.departments.store') }}" class="space-y-4">
            @csrf
            
            <!-- College / Sanstha Selection -->
            <div>
                @if($colleges->isEmpty())
                    <div class="p-4 bg-amber-50 border border-amber-200 rounded-lg mb-4">
                        <p class="text-sm text-amber-800">No colleges found. Please create a Sanstha/College first or enter a name below to create one.</p>
                    </div>
                    <x-form-input 
                        name="new_sanstha_name"
                        label="Create Sanstha"
                        placeholder="Enter Sanstha/College name to create and link"
                        helpText="If you provide a name here the Sanstha will be created and the department will be linked to it automatically."
                    />
                @else
                    <x-form-select 
                        name="college_id"
                        label="College (or enter a new name below)"
                    >
                        <option value="">-- Select College --</option>
                        @foreach($colleges as $c)
                            <option value="{{ $c->id }}" {{ old('college_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </x-form-select>

                    <div class="mt-6 pt-4 border-t theme-border-primary">
                        <x-form-input 
                            name="new_sanstha_name"
                            label="Or create and link a new Sanstha"
                            placeholder="Optional: create new Sanstha and link"
                            helpText="If you enter a name here it will create a new Sanstha and the department will be linked to it."
                        />
                    </div>
                @endif
            </div>

            <x-form-input 
                name="name"
                label="Department Name"
                required
                placeholder="Enter department name"
            />

            <x-form-textarea 
                name="description"
                label="Description"
                rows="4"
                placeholder="Enter department description"
            >{{ old('description') }}</x-form-textarea>

            <div class="flex gap-3 pt-4">
                <x-form-button type="submit" variant="primary">
                    Save Department
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
