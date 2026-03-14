@extends('layouts.app')

@section('title','Add Vendor')

@section('content')
<x-theme-container class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Add Vendor" icon="plus" />

    <div class="theme-card p-6">
        <form method="POST" action="{{ route('admin.vendors.store') }}" class="space-y-4">
            @csrf

            <x-form-input 
                name="name"
                label="Name"
                required
                placeholder="Vendor name"
            />

            <x-form-input 
                name="company_name"
                label="Company Name"
                placeholder="Company name"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input 
                    name="phone"
                    label="Phone"
                    placeholder="+91-999999999"
                />

                <x-form-input 
                    name="email"
                    label="Email"
                    type="email"
                    placeholder="vendor@example.com"
                />
            </div>

            <x-form-textarea 
                name="address"
                label="Address"
                rows="3"
                placeholder="Full address"
            />

            <x-form-input 
                name="gst_number"
                label="GST Number"
                placeholder="XX AABBBB0000A1Z5"
            />

            <div class="flex gap-3 pt-4">
                <x-form-button type="submit" variant="primary">
                    Save Vendor
                </x-form-button>
                <x-action-button 
                    href="{{ route('admin.vendors.index') }}"
                    type="secondary"
                >
                    Cancel
                </x-action-button>
            </div>
        </form>
    </div>
</x-theme-container>
@endsection
