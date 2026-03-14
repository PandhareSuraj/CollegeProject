@extends('layouts.app')

@section('title', 'Create Stationary Request')

@section('content')
<x-theme-container class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header 
        title="Create New Request"
        icon="arrow-right"
    />

    <x-form-card 
        title="Request Details"
        action="{{ route('requests.store') }}"
        method="POST"
        id="requestForm"
    >
        <x-form-select
            label="Department"
            name="department_id"
            :options="$departments->pluck('name', 'id')"
            placeholder="Select Department"
            required
        />

        <x-form-input
            label="Requested By"
            name="requested_by"
            type="text"
            :value="Auth::user()->name"
            readonly
        />

        <x-request-item-row :products="$products" />

        <x-form-actions
            submitText="Submit Request"
            cancelRoute="{{ route('requests.index') }}"
        />
    </x-form-card>
</x-theme-container>
@endsection
