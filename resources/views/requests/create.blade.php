@extends('layouts.app')

@section('title', 'Create Stationary Request')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold text-gray-900 dark:text-white dark:text-gray-300 dark:text-white mb-8 flex items-center gap-3">
        <svg class="w-10 h-10 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
        Create New Request
    </h1>

    <div class="max-w-3xl mx-auto">
        <div class="theme-card rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-300 mb-6">Request Details</h2>

            <form action="{{ route('requests.store') }}" method="POST" id="requestForm" class="space-y-6">
                @csrf

                <!-- Department Selection -->
                <div>
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department</label>
                    <select name="department_id" id="department_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('department_id') border-red-500 @enderror" required>
                        <option value="">Select Department</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requested By -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Requested By</label>
                    <div class="px-4 py-2 bg-gray-50 rounded-lg text-gray-900 dark:text-gray-300">{{ Auth::user()->name }}</div>
                </div>

                <!-- Items Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white dark:text-gray-300 dark:text-white mb-4">Items</h3>
                    <div id="itemsContainer" class="space-y-4">
                        <div class="item-row px-4 py-4 border border-gray-200 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                                    <select name="items[0][product_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                                {{ $product->name }} (₹{{ number_format($product->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                    <input type="number" name="items[0][quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent quantity-input" min="1" required>
                                </div>
                                <div class="md:col-span-1">
                                    <button type="button" class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition remove-item" style="display:none;">
                                        <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition" id="addItem">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
                        </svg>
                        Add Another Item
                    </button>
                </div>

                <!-- Total Amount -->
                <div class="px-6 py-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="text-lg font-semibold text-gray-900 dark:text-white dark:text-gray-300 dark:text-white">
                        Total Amount: <span id="totalAmount" class="text-green-600">₹0.00</span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200">
                    <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-blue-600 text-white hover:bg-blue-700 dark:hover:bg-blue-800 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-1.488 5.987 1.495a1 1 0 001.187-1.405l-7-14z"/>
                        </svg>
                        Submit Request
                    </button>
                    <a href="{{ route('requests.index') }}" class="flex items-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let itemCount = 1;

function updateTotal() {
    let total = 0;
    document.querySelectorAll('.item-row').forEach(row => {
        const select = row.querySelector('.product-select');
        const qty = row.querySelector('.quantity-input');
        if (select.value && qty.value) {
            const price = parseFloat(select.options[select.selectedIndex].dataset.price) || 0;
            total += price * parseInt(qty.value);
        }
    });
    document.getElementById('totalAmount').textContent = '₹' + total.toFixed(2);
}

document.getElementById('addItem').addEventListener('click', function() {
    const container = document.getElementById('itemsContainer');
    const newRow = document.createElement('div');
    newRow.className = 'item-row mb-3';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="form-label">Product</label>
                <select name="items[${itemCount}][product_id]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent  product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} (₹{{ number_format($product->price, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="form-label">Quantity</label>
                <input type="number" name="items[${itemCount}][quantity]" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent  quantity-input" min="1" required>
            </div>
            <div class="md:col-span-1">
                <label class="form-label">&nbsp;</label>
                <button type="button" class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition remove-item">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
    itemCount++;
    updateRemoveButtons();
    attachEventListeners();
});

function updateRemoveButtons() {
    const items = document.querySelectorAll('.item-row');
    items.forEach((item, index) => {
        const btn = item.querySelector('.remove-item');
        btn.style.display = items.length > 1 ? 'block' : 'none';
    });
}

function attachEventListeners() {
    document.querySelectorAll('.product-select, .quantity-input').forEach(el => {
        el.addEventListener('change', updateTotal);
        el.addEventListener('input', updateTotal);
    });

    document.querySelectorAll('.remove-item').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.item-row').remove();
            updateTotal();
            updateRemoveButtons();
        });
    });
}

updateRemoveButtons();
attachEventListeners();
updateTotal();
</script>
@endpush
