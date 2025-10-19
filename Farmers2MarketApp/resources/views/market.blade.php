@extends('layouts.app')

@section('title', 'Marketplace - AgriConnect')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">🌾 Welcome to Marketplace!</h1>
            <p class="text-green-700 dark:text-green-400 mt-1">Explore a wide variety of crops available from trusted farmers.</p>
        </div>
        <button id="cartButton" class="relative flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold transition">
            🛒 Cart (<span id="cartCount">0</span>)
        </button>
    </div>

    <!-- Crops Grid -->
    <div id="cropsGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($crops as $crop)
        <div class="crop-card bg-white dark:bg-gray-800 border rounded-xl overflow-hidden shadow transition">
        <img src="{{ asset('uploads/crops/' . ($crop->image ?? 'default_crop.jpg')) }}"
     alt="{{ $crop->crop_name }}"
     class="w-full h-40 object-cover">

            <div class="p-4 flex flex-col gap-2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $crop->crop_name }}</h3>
                <p class="text-gray-700 dark:text-gray-300 text-sm">{{ Str::limit($crop->description ?? 'No description', 80) }}</p>
                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $crop->status == 'Approved' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                    {{ $crop->status }}
                </span>
                <p class="text-sm text-gray-800 dark:text-gray-200">Available: {{ $crop->quantity_available }} kg</p>
                <p class="text-sm text-gray-800 dark:text-gray-200 font-semibold">Price: Rs. {{ $crop->price }}/kg</p>
                <button class="mt-2 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-semibold order-btn transition"
                        data-crop-id="{{ $crop->crop_id ?? $crop->id }}"
                        data-crop-name="{{ $crop->crop_name }}"
                        data-crop-price="{{ $crop->price }}"
                        data-crop-quantity="{{ $crop->quantity_available }}">
                        🛒 Order
                </button>
            </div>
        </div>
        @empty
        <p class="text-gray-800 dark:text-gray-200 col-span-full text-center">No crops available at the moment.</p>
        @endforelse
    </div>
</div>

<!-- ================= ORDER MODAL ================= -->
<div id="orderModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-96 relative shadow-lg">
        <button id="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 text-2xl">&times;</button>
        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-gray-100" id="modalCropName"></h3>
        <p class="text-gray-800 dark:text-gray-200">Price: Rs. <span id="modalCropPrice"></span> /kg</p>
        <p class="mb-4 text-gray-800 dark:text-gray-200">Available: <span id="modalCropQuantity"></span> kg</p>
        <label class="block mb-1 font-medium text-gray-800 dark:text-gray-200">Select Quantity (kg):</label>
        <input type="number" id="orderQuantity" min="1" step="1" value="1" class="w-full px-3 py-2 mb-4 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600">
        <div class="flex gap-2">
            <button id="addToCart" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">Add to Cart</button>
            <button id="buyNow" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">Buy Now</button>
        </div>
    </div>
</div>

<!-- ================= CART MODAL ================= -->
<div id="cartModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-xl w-96 relative max-h-[80vh] overflow-y-auto shadow-lg">
        <button id="closeCart" class="absolute top-2 right-2 text-gray-500 hover:text-gray-900 dark:text-gray-300 dark:hover:text-gray-100 text-2xl">&times;</button>
        <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">🛍️ Your Cart</h3>
        <div id="cartItems" class="flex flex-col gap-3 mb-4 text-gray-800 dark:text-gray-200">Your cart is empty.</div>
        <p class="font-semibold text-right text-gray-900 dark:text-gray-100">Total: Rs. <span id="cartTotal">0</span></p>
        <button id="checkoutCart" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold mt-2 transition">Proceed to Checkout</button>
    </div>
</div>

<!-- ================= HIDDEN CHECKOUT FORM ================= -->
<form id="checkoutForm" action="{{ route('order.checkout') }}" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="cart" id="checkoutCartInput">
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

    // Open order modal
    document.getElementById('cropsGrid').addEventListener('click', e => {
        const btn = e.target.closest('.order-btn');
        if (!btn) return;
        
        currentCrop = {
            id: btn.dataset.cropId,
            name: btn.dataset.cropName,
            price: parseFloat(btn.dataset.cropPrice),
            maxQty: parseFloat(btn.dataset.cropQuantity)
        };

        console.log('Selected crop:', currentCrop);

        elements.modalCropName.textContent = currentCrop.name;
        elements.modalCropPrice.textContent = currentCrop.price;
        elements.modalCropQuantity.textContent = currentCrop.maxQty;
        elements.orderQuantity.value = 1;
        elements.orderQuantity.max = currentCrop.maxQty;
        elements.orderModal.classList.remove('hidden');
    });

    // Close modals
    document.getElementById('closeModal').addEventListener('click', () => {
        elements.orderModal.classList.add('hidden');
    });
    
    document.getElementById('closeCart').addEventListener('click', () => {
        elements.cartModal.classList.add('hidden');
    });

    // Click outside to close modals
    elements.orderModal.addEventListener('click', (e) => {
        if (e.target === elements.orderModal) {
            elements.orderModal.classList.add('hidden');
        }
    });

    elements.cartModal.addEventListener('click', (e) => {
        if (e.target === elements.cartModal) {
            elements.cartModal.classList.add('hidden');
        }
    });

    // Add item to cart
    const addItemToCart = (crop, qty) => {
        qty = parseFloat(qty);
        
        if (isNaN(qty) || qty < 1) {
            alert('Please enter a valid quantity');
            return false;
        }
        
        if (qty > crop.maxQty) {
            alert(`Only ${crop.maxQty} kg available for ${crop.name}`);
            return false;
        }

        // Find if crop already exists in cart
        const existingIndex = cart.findIndex(item => String(item.id) === String(crop.id));
        
        if (existingIndex >= 0) {
            // Update existing item quantity
            const newQty = cart[existingIndex].qty + qty;
            if (newQty > crop.maxQty) {
                alert(`Cannot add more. Maximum available: ${crop.maxQty} kg`);
                return false;
            }
            cart[existingIndex].qty = newQty;
            console.log('Updated cart item:', cart[existingIndex]);
        } else {
            // Add new item to cart
            const newItem = {
                id: crop.id,
                name: crop.name,
                price: crop.price,
                qty: qty
            };
            cart.push(newItem);
            console.log('Added to cart:', newItem);
        }
        
        console.log('Current cart:', cart);
        updateCartUI();
        return true;
    };

    // Update Cart UI
    const updateCartUI = () => {
        elements.cartItems.innerHTML = '';
        
        if (cart.length === 0) {
            elements.cartItems.innerHTML = '<p class="text-gray-600 dark:text-gray-400">Your cart is empty.</p>';
            elements.cartCount.textContent = '0';
            elements.cartTotal.textContent = '0';
            return;
        }

        let totalAmount = 0;
        let totalItems = 0;

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.qty;
            totalAmount += itemTotal;
            totalItems += item.qty;

            const div = document.createElement('div');
            div.className = 'flex justify-between items-center border-b pb-2 dark:border-gray-700';
            div.innerHTML = `
                <div class="flex-1">
                    <p class="font-medium">${item.name}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">${item.qty} kg × Rs. ${item.price}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-semibold">Rs. ${itemTotal.toFixed(2)}</span>
                    <button class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-bold remove-btn" data-index="${index}" title="Remove item">&times;</button>
                </div>
            `;
            elements.cartItems.appendChild(div);
        });

        elements.cartCount.textContent = cart.length;
        elements.cartTotal.textContent = totalAmount.toFixed(2);

        // Attach remove button listeners
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const index = parseInt(btn.dataset.index);
                console.log('Removing item at index:', index);
                cart.splice(index, 1);
                updateCartUI();
            });
        });
    };

    // Checkout function
    const processCheckout = () => {
        if (cart.length === 0) {
            alert('Your cart is empty!');
            return false;
        }

        try {
            // Prepare cart data for session storage
            const cartData = cart.map(item => ({
                id: item.id,
                name: item.name,
                price: parseFloat(item.price),
                quantity: parseFloat(item.qty)
            }));

            console.log('Cart data being sent:', cartData);
            console.log('Form element:', elements.checkoutForm);
            console.log('Cart input element:', elements.checkoutCartInput);

            elements.checkoutCartInput.value = JSON.stringify(cartData);
            
            console.log('Cart input value set to:', elements.checkoutCartInput.value);
            console.log('Form action:', elements.checkoutForm.action);
            console.log('Form method:', elements.checkoutForm.method);

            // Add visual feedback
            const checkoutBtn = document.getElementById('checkoutCart');
            if (checkoutBtn) {
                checkoutBtn.disabled = true;
                checkoutBtn.textContent = 'Processing...';
            }

            console.log('Submitting form now...');
            
            // Submit the form to checkout page
            elements.checkoutForm.submit();
            
            console.log('Form submitted');
            
            return true;
        } catch (error) {
            console.error('Checkout error:', error);
            alert('An error occurred during checkout. Please try again.');
            
            // Re-enable button
            const checkoutBtn = document.getElementById('checkoutCart');
            if (checkoutBtn) {
                checkoutBtn.disabled = false;
                checkoutBtn.textContent = 'Proceed to Checkout';
            }
            
            return false;
        }
    };

    // Add to cart button inside modal
    document.getElementById('addToCart').addEventListener('click', (e) => {
        e.preventDefault();
        const qty = parseFloat(elements.orderQuantity.value);
        console.log('Add to cart clicked, qty:', qty);
        
        if (addItemToCart(currentCrop, qty)) {
            elements.orderModal.classList.add('hidden');
            alert(`${qty} kg of ${currentCrop.name} added to cart!`);
        }
    });

    // Buy Now button inside modal
    document.getElementById('buyNow').addEventListener('click', (e) => {
        e.preventDefault();
        const qty = parseFloat(elements.orderQuantity.value);
        console.log('Buy now clicked, qty:', qty);
        
        if (addItemToCart(currentCrop, qty)) {
            elements.orderModal.classList.add('hidden');
            setTimeout(() => {
                processCheckout();
            }, 100);
        }
    });

    // Checkout button in cart modal
    document.getElementById('checkoutCart').addEventListener('click', (e) => {
        e.preventDefault();
        console.log('Checkout cart clicked');
        console.log('Current cart state:', cart);
        
        if (cart.length === 0) {
            alert('Your cart is empty!');
            return;
        }
        
        processCheckout();
    });

    // Open cart modal
    document.getElementById('cartButton').addEventListener('click', (e) => {
        e.preventDefault();
        elements.cartModal.classList.remove('hidden');
    });

    // Initialize cart on page load
    updateCartUI();
    
    console.log('Cart system initialized');
});
</script>
@endpush
@endsection