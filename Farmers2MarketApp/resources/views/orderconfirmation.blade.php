@extends('layouts.app')

@section('title', 'Order Confirmation - AgriConnect')

@section('content')
<div class="container mx-auto px-6 py-10 text-center">
    <h1 class="text-3xl font-bold mb-4 text-green-600">🎉 Order Placed Successfully!</h1>
    <p class="text-lg mb-2">Thank you, {{ Auth::user()->name }}!</p>

    @if(isset($order))
        <p class="mb-2 font-medium">Order ID: #{{ $order->id }}</p>
        <p class="mb-2">Crop: {{ $order->crop->name }}</p>
        <p class="mb-2">Quantity: {{ $order->quantity }} kg</p>
        <p class="mb-2">Total Amount: Rs. {{ number_format($order->total_amount, 2) }}</p>
        <p class="mb-4">Payment Method: {{ $order->payment_method }}</p>
    @endif

    <a href="{{ route('market') }}" class="inline-block bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
        🛒 Back to Marketplace
    </a>
</div>
@endsection
