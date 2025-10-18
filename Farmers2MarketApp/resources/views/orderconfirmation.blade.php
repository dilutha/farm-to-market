@extends('layouts.app')
@section('title', 'Order Confirmation - AgriConnect')
@section('content')
<div class="container mx-auto px-6 py-10">
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="mx-auto w-20 h-20 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mb-4">
                <svg class="w-12 h-12 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-green-600 dark:text-green-400 mb-2">Order Placed Successfully!</h1>
            <p class="text-gray-600 dark:text-gray-400">Thank you for your order, {{ Auth::user()->name }}!</p>
        </div>

        @if(isset($order) && $order)
            <div class="border-t border-b dark:border-gray-700 py-6 mb-6">
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Order ID</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">#{{ $order->order_id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Order Date</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status</p>
                        <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                            @if($order->status == 'Pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'Approved') bg-green-100 text-green-800
                            @elseif($order->status == 'Delivered') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ $order->status }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Payment Method</p>
                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                            @if($order->payment)
                                {{ $order->payment->payment_method }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="font-semibold text-lg mb-3 text-gray-900 dark:text-gray-100">Order Items</h3>
                    <div class="space-y-3">
                        @foreach($order->orderItems as $item)
                            <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $item->crop->crop_name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $item->quantity }} kg × Rs. {{ number_format($item->unit_price, 2) }}</p>
                                </div>
                                <p class="font-semibold text-gray-900 dark:text-gray-100">Rs. {{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t dark:border-gray-600">
                    <div class="flex justify-between items-center">
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total Amount</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400">Rs. {{ number_format($order->total_price, 2) }}</p>
                    </div>
                </div>
            </div>

            @if($order->payment && $order->payment->payment_status == 'Pending')
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                    <p class="text-yellow-800 dark:text-yellow-300">
                        <strong>Payment Pending:</strong> Please keep the exact amount ready for Cash on Delivery.
                    </p>
                </div>
            @elseif($order->payment && $order->payment->payment_status == 'Completed')
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                    <p class="text-green-800 dark:text-green-300">
                        <strong>Payment Completed:</strong> Your payment has been successfully processed.
                    </p>
                </div>
            @endif

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                <h4 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">What's Next?</h4>
                <ul class="list-disc list-inside text-blue-800 dark:text-blue-300 space-y-1">
                    <li>We'll process your order shortly</li>
                    <li>You'll receive a confirmation email</li>
                    <li>Track your order status in your dashboard</li>
                    <li>Delivery will be scheduled soon</li>
                </ul>
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-600 dark:text-gray-400">Order details not found.</p>
            </div>
        @endif

        <div class="flex gap-4">
            <a href="{{ route('buyer.dashboard') }}" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                📊 View My Orders
            </a>
            <a href="{{ route('market') }}" class="flex-1 bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold text-center transition">
                🛒 Continue Shopping
            </a>
        </div>
    </div>
</div>
@endsection