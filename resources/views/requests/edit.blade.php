@extends('layouts.app')

@section('title', 'Edit Request #' . $stationaryRequest->id)

@section('content')
<div class="mb-4">
    <h1 class="text-3xl font-bold"><i class="fas fa-edit"></i> Edit Request #{{ $stationaryRequest->id }}</h1>
</div>

<div class="max-w-4xl">
    <div class="bg-white rounded-lg border border-gray-200 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
            <h5 class="font-semibold text-lg">Update Request Items</h5>
        </div>
        <div class="p-6">
            <form action="{{ route('requests.update', $stationaryRequest->id) }}" method="POST" id="requestForm">
                @csrf
                @method('PUT')

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        <strong>Department:</strong> {{ $stationaryRequest->department->name }}
                    </label>
                </div>

                <h5 class="text-lg font-semibold mb-4 mt-6">Items</h5>
                <div id="itemsContainer" class="space-y-4">
                    @foreach($stationaryRequest->items as $index => $item)
                        <div class="item-row">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                                    <select name="items[{{ $index }}][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 product-select" required>
                                        <option value="">Select Product</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" 
                                                {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                                {{ $product->name }} (₹{{ number_format($product->price, 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                                    <input type="number" name="items[{{ $index }}][quantity]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 quantity-input" 
                                        value="{{ $item->quantity }}" min="1" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                                    <button type="button" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition remove-item">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="mt-4 px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition" id="addItem">
                    <i class="fas fa-plus"></i> Add Another Item
                </button>

                <div class="mt-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h5 class="text-lg font-semibold">Total Amount: <span id="totalAmount" class="text-green-600">₹{{ number_format($stationaryRequest->total_amount, 2) }}</span></h5>
                </div>

                <div class="mt-6 flex gap-4">
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        <i class="fas fa-save"></i> Update Request
                    </button>
                    <a href="{{ route('requests.show', $stationaryRequest->id) }}" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let itemCount = {{ count($stationaryRequest->items) }};

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
    newRow.className = 'item-row';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Product</label>
                <select name="items[${itemCount}][product_id]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} (₹{{ number_format($product->price, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                <input type="number" name="items[${itemCount}][quantity]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 quantity-input" min="1" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">&nbsp;</label>
                <button type="button" class="w-full px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium transition remove-item">
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
