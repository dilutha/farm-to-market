@extends('layouts.app')

@section('title', 'Marketplace - AgriConnect')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                🌾 Welcome to Marketplace!
            </h1>
            <p class="text-green-700 dark:text-green-400 mt-1">
                Explore a wide variety of crops available from trusted farmers.
            </p>
        </div>

        <button id="cartButton"
            class="relative flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
            🛒 Cart (<span id="cartCount">0</span>)
        </button>
    </div>

    <!-- Crops Grid -->
    <div id="cropsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($crops as $crop)
        <div class="crop-card bg-white dark:bg-gray-800 border rounded-xl overflow-hidden shadow transition"
             data-name="{{ strtolower($crop->crop_name) }}"
             data-price="{{ $crop->price }}">
             
            <img src="{{ asset('storage/crops/' . ($crop->image ?: 'default_crop.jpg')) }}"
                 alt="{{ $crop->crop_name }}"
                 class="w-full h-40 object-cover">

            <div class="p-4 flex flex-col gap-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $crop->crop_name }}</h3>
                <p class="text-gray-700 dark:text-gray-300 text-sm">
                    {{ Str::limit($crop->description ?? 'No description available', 80) }}
                </p>

                <span class="px-2 py-1 text-xs font-semibold rounded-full 
                    {{ $crop->status == 'Approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                    {{ $crop->status }}
                </span>

                <p class="text-sm text-gray-800 dark:text-gray-200">Available: {{ $crop->quantity_available }} kg</p>
                <p class="text-sm text-gray-800 dark:text-gray-200 font-semibold">
                    Price: Rs. {{ $crop->price }}/kg
                </p>

                <button class="mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold order-btn transition"
                        data-crop-id="{{ $crop->id }}"
                        data-crop-name="{{ $crop->crop_name }}"
                        data-crop-price="{{ $crop->price }}"
                        data-crop-quantity="{{ $crop->quantity_available }}">
                        🛒 Order
                </button>
            </div>
        </div>
        @empty
        <p class="text-gray-800 dark:text-gray-200 col-span-full text-center">
            No crops available at the moment.
        </p>
        @endforelse
    </div>
</div>

<!-- ================= ORDER MODAL ================= -->
<div id="orderModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-96 relative shadow-lg">
        <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 text-2xl">&times;</button>

        <h3 class="text-xl font-bold mb-3" id="modalCropName"></h3>
        <p>Price: Rs. <span id="modalCropPrice"></span> /kg</p>
        <p class="mb-4">Available: <span id="modalCropQuantity"></span> kg</p>

        <label class="block mb-1 font-medium">Select Quantity (kg):</label>
        <input type="number" id="orderQuantity" min="1" value="1"
               class="w-full px-3 py-2 mb-4 border rounded-lg focus:ring-2 focus:ring-green-500">

        <div class="flex gap-2">
            <button id="addToCart" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
                Add to Cart
            </button>
            <button id="checkout" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                Checkout
            </button>
        </div>
    </div>
</div>

<!-- ================= CART MODAL ================= -->
<div id="cartModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-96 relative max-h-[80vh] overflow-y-auto shadow-lg">
        <button id="closeCart" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 text-2xl">&times;</button>
        <h3 class="text-xl font-bold mb-4">🛍️ Your Cart</h3>
        <div id="cartItems" class="flex flex-col gap-3 mb-4 text-gray-800 dark:text-gray-200">
            Your cart is empty.
        </div>
        <p class="font-semibold text-right">Total: Rs. <span id="cartTotal">0</span></p>
        <button id="checkoutCart"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold mt-2 transition">
            Proceed to Checkout
        </button>
    </div>
</div>

<!-- ================= HIDDEN CHECKOUT FORM ================= -->
<form id="checkoutForm" action="{{ route('order.place') }}" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="cart" id="checkoutCartInput">
    <input type="hidden" name="total_amount" id="checkoutTotalInput">
    <input type="hidden" name="payment_method" value="COD">
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    let cart = [];
    let currentCrop = null;

    const elements = {
        orderModal: document.getElementById('orderModal'),
        cartModal: document.getElementById('cartModal'),
        modalCropName: document.getElementById('modalCropName'),
        modalCropPrice: document.getElementById('modalCropPrice'),
        modalCropQuantity: document.getElementById('modalCropQuantity'),
        orderQuantity: document.getElementById('orderQuantity'),
        cartCount: document.getElementById('cartCount'),
        cartItems: document.getElementById('cartItems'),
        cartTotal: document.getElementById('cartTotal'),
        checkoutForm: document.getElementById('checkoutForm'),
        checkoutCartInput: document.getElementById('checkoutCartInput'),
        checkoutTotalInput: document.getElementById('checkoutTotalInput')
    };

    // --- Crop Order Modal ---
    const cropsGrid = document.getElementById('cropsGrid');
    cropsGrid.addEventListener('click', e => {
        if (e.target.classList.contains('order-btn')) {
            const btn = e.target;
            currentCrop = {
                id: btn.dataset.cropId,
                name: btn.dataset.cropName,
                price: parseFloat(btn.dataset.cropPrice),
                maxQty: parseFloat(btn.dataset.cropQuantity)
            };
            elements.modalCropName.textContent = currentCrop.name;
            elements.modalCropPrice.textContent = currentCrop.price;
            elements.modalCropQuantity.textContent = currentCrop.maxQty;
            elements.orderQuantity.value = 1;
            elements.orderQuantity.max = currentCrop.maxQty;
            elements.orderModal.classList.remove('hidden');
        }
    });

    document.getElementById('closeModal').addEventListener('click', () => elements.orderModal.classList.add('hidden'));
    document.getElementById('closeCart').addEventListener('click', () => elements.cartModal.classList.add('hidden'));

    // --- Add to Cart ---
    document.getElementById('addToCart').addEventListener('click', () => {
        const qty = parseFloat(elements.orderQuantity.value);
        if (qty < 1 || qty > currentCrop.maxQty) return alert('Invalid quantity selected.');

        const existing = cart.find(i => i.id === currentCrop.id);
        if (existing) existing.qty = Math.min(existing.qty + qty, currentCrop.maxQty);
        else cart.push({ ...currentCrop, qty });

        updateCartUI();
        elements.orderModal.classList.add('hidden');
    });

    function updateCartUI() {
        if (cart.length === 0) {
            elements.cartItems.textContent = 'Your cart is empty.';
            elements.cartCount.textContent = 0;
            elements.cartTotal.textContent = 0;
            return;
        }

        elements.cartItems.innerHTML = '';
        let total = 0, count = 0;
        cart.forEach((item, i) => {
            total += item.price * item.qty;
            count += item.qty;
            const div = document.createElement('div');
            div.className = 'flex justify-between items-center';
            div.innerHTML = `
                <span>${item.name} x ${item.qty} kg</span>
                <div class="flex items-center gap-2">
                    <span>Rs. ${(item.qty * item.price).toFixed(2)}</span>
                    <button class="text-red-600 hover:text-red-800 font-bold remove-btn" data-index="${i}">&times;</button>
                </div>
            `;
            elements.cartItems.appendChild(div);
        });

        elements.cartCount.textContent = count;
        elements.cartTotal.textContent = total.toFixed(2);

        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                cart.splice(btn.dataset.index, 1);
                updateCartUI();
            });
        });
    }

    function proceedToOrderDetails() {
        if (cart.length === 0) return alert('Your cart is empty!');
        const total = cart.reduce((sum, item) => sum + item.price * item.qty, 0);
        elements.checkoutCartInput.value = JSON.stringify(cart);
        elements.checkoutTotalInput.value = total.toFixed(2);
        elements.checkoutForm.submit();
    }

    document.getElementById('checkout').addEventListener('click', () => {
        const qty = parseFloat(elements.orderQuantity.value);
        if (qty < 1 || qty > currentCrop.maxQty) return alert('Invalid quantity selected.');
        cart = [{ ...currentCrop, qty }]; // single item checkout
        proceedToOrderDetails();
    });

    document.getElementById('checkoutCart').addEventListener('click', proceedToOrderDetails);
    document.getElementById('cartButton').addEventListener('click', () => elements.cartModal.classList.remove('hidden'));
});
</script>
@endpush
@endsection
