@extends('layouts.app')
@section('title', 'Checkout - AgriConnect')
@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-900 dark:text-gray-100">🛒 Checkout</h1>
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif
    
    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @php
        $cart = session('cart', []);
    @endphp
    
    @if(count($cart) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-gray-100">Order Summary</h2>
            <ul class="mb-4">
                @foreach($cart as $item)
                <li class="flex justify-between border-b dark:border-gray-700 py-3 text-gray-800 dark:text-gray-200">
                    <span>{{ $item['name'] }} × {{ $item['quantity'] }} kg</span>
                    <span class="font-semibold">Rs. {{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                </li>
                @endforeach
            </ul>
            
            @php
                $totalAmount = array_sum(array_map(fn($i) => $i['price'] * $i['quantity'], $cart));
            @endphp
            
            <div class="border-t dark:border-gray-700 pt-4 mb-6">
                <p class="font-bold text-right text-xl text-gray-900 dark:text-gray-100">
                    Total: Rs. {{ number_format($totalAmount, 2) }}
                </p>
            </div>

            <!-- Payment Method Selection -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-gray-100">Select Payment Method</h3>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition payment-option" data-method="COD">
                        <input type="radio" name="payment_method" value="COD" class="mr-3 w-5 h-5" checked>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">💵 Cash on Delivery (COD)</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pay when you receive your order</p>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 border-2 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition payment-option" data-method="PayHere">
                        <input type="radio" name="payment_method" value="PayHere" class="mr-3 w-5 h-5">
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">💳 Pay with PayHere</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Secure online payment via Credit/Debit Card or Mobile</p>
                        </div>
                    </label>
                </div>
            </div>

            <form id="orderForm" action="{{ route('order.place') }}" method="POST">
                @csrf
                <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
                <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
                <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="COD">
                
                <!-- Debug: Show what will be submitted -->
                @if(config('app.debug'))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 text-sm">
                    <p><strong>Debug - Form Data:</strong></p>
                    <p>Action: {{ route('order.place') }}</p>
                    <p>Cart items: {{ count($cart) }}</p>
                    <p>Total: Rs. {{ $totalAmount }}</p>
                    <p id="debugPaymentMethod">Payment: COD</p>
                </div>
                @endif
                
                <div class="flex gap-4">
                    <a href="{{ route('market') }}" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                        ← Back to Market
                    </a>
                    <button type="submit" id="placeOrderBtn" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        Place Order
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 text-center">
            <p class="text-gray-800 dark:text-gray-200 mb-4">Your cart is empty!</p>
            <a href="{{ route('market') }}" class="inline-block bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                Go to Marketplace
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const paymentOptions = document.querySelectorAll('.payment-option');
    const selectedMethodInput = document.getElementById('selectedPaymentMethod');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const orderForm = document.getElementById('orderForm');

    paymentOptions.forEach(option => {
        option.addEventListener('click', () => {
            const radio = option.querySelector('input[type="radio"]');
            radio.checked = true;
            const method = option.dataset.method;
            selectedMethodInput.value = method;
            
            console.log('Payment method selected:', method);
            
            // Update button text based on payment method
            if (method === 'COD') {
                placeOrderBtn.textContent = 'Place Order (COD)';
            } else {
                placeOrderBtn.textContent = 'Proceed to Payment';
            }
            
            // Update border styles
            paymentOptions.forEach(opt => {
                opt.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                opt.classList.add('border-gray-300', 'dark:border-gray-600');
            });
            option.classList.remove('border-gray-300', 'dark:border-gray-600');
            option.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
        });
    });

    // Trigger click on checked option to set initial state
    const checkedOption = document.querySelector('.payment-option input:checked');
    if (checkedOption) {
        checkedOption.closest('.payment-option').click();
    }

    // Form submission handler
    orderForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const formData = new FormData(orderForm);
        console.log('Form submission:');
        console.log('Cart:', formData.get('cart'));
        console.log('Total:', formData.get('total_amount'));
        console.log('Payment Method:', formData.get('payment_method'));
        
        // Disable button to prevent double submission
        placeOrderBtn.disabled = true;
        placeOrderBtn.textContent = 'Processing...';
        
        // Submit the form
        orderForm.submit();
    });
});
</script>
@endpush
@endsection