@extends('layouts.app')

@section('title','Edit Vendor')

@section('content')
<x-theme-container class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <x-page-header title="Edit Vendor" icon="pencil" />

    <div class="theme-card p-6">
        <form method="POST" action="{{ route('admin.vendors.update', $vendor->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <x-form-input 
                name="name"
                label="Name"
                required
                :value="old('name', $vendor->name)"
                placeholder="Vendor name"
            />

            <x-form-input 
                name="company_name"
                label="Company Name"
                :value="old('company_name', $vendor->company_name)"
                placeholder="Company name"
            />

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-form-input 
                    name="phone"
                    label="Phone"
                    :value="old('phone', $vendor->phone)"
                    placeholder="+91-999999999"
                />

                <x-form-input 
                    name="email"
                    label="Email"
                    type="email"
                    :value="old('email', $vendor->email)"
                    placeholder="vendor@example.com"
                />
            </div>

            <x-form-textarea 
                name="address"
                label="Address"
                rows="3"
                placeholder="Full address"
            >{{ old('address', $vendor->address) }}</x-form-textarea>

            <x-form-input 
                name="gst_number"
                label="GST Number"
                :value="old('gst_number', $vendor->gst_number)"
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
