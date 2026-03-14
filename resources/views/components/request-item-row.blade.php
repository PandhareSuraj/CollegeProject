@props(['products' => []])

<div>
    <h3 class="text-lg font-semibold theme-text-primary mb-4">Items</h3>
    <div id="itemsContainer" class="space-y-4">
        <div class="item-row px-4 py-4 border theme-border-primary rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium theme-text-primary mb-2">Product</label>
                    <select name="items[0][product_id]" class="w-full px-4 py-2 theme-input theme-border-primary rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent product-select" required>
                        <option value="">Select Product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                                {{ $product->name }} (₹{{ number_format($product->price, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium theme-text-primary mb-2">Quantity</label>
                    <input type="number" name="items[0][quantity]" class="w-full px-4 py-2 theme-input theme-border-primary rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent quantity-input" min="1" required>
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

    <button type="button" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition" id="addItem">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"/>
        </svg>
        Add Another Item
    </button>

    <!-- Total Amount -->
    <div class="mt-6 px-6 py-4 theme-bg-secondary rounded-lg border theme-border-primary">
        <div class="text-lg font-semibold theme-text-primary">
            Total Amount: <span id="totalAmount" class="text-green-600">₹0.00</span>
        </div>
    </div>
</div>

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
    newRow.className = 'item-row px-4 py-4 border theme-border-primary rounded-lg';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium theme-text-primary mb-2">Product</label>
                <select name="items[${itemCount}][product_id]" class="w-full px-4 py-2 theme-input theme-border-primary rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent product-select" required>
                    <option value="">Select Product</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} (₹{{ number_format($product->price, 2) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium theme-text-primary mb-2">Quantity</label>
                <input type="number" name="items[${itemCount}][quantity]" class="w-full px-4 py-2 theme-input theme-border-primary rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent quantity-input" min="1" required>
            </div>
            <div class="md:col-span-1">
                <button type="button" class="w-full px-4 py-2 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition remove-item">
                    <svg class="w-5 h-5 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
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
