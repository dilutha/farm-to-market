@extends('layouts.app')

@section('title', 'Checkout - AgriConnect')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-6">🛒 Checkout</h1>

    @php $cart = session('cart', []); @endphp

    @if(count($cart) > 0)
        <ul class="mb-4">
            @foreach($cart as $item)
                <li class="flex justify-between border-b py-2">
                    <span>{{ $item['name'] }} x {{ $item['quantity'] }} kg</span>
                    <span>Rs. {{ $item['price'] * $item['quantity'] }}</span>
                </li>
            @endforeach
        </ul>

        @php
            $totalAmount = array_sum(array_map(fn($i)=>$i['price']*$i['quantity'], $cart));
        @endphp
        <p class="font-bold text-right mb-4">Total: Rs. {{ $totalAmount }}</p>

        <form action="{{ route('order.place') }}" method="POST">
            @csrf
            <input type="hidden" name="cart" value="{{ json_encode($cart) }}">
            <input type="hidden" name="total_amount" value="{{ $totalAmount }}">
            <input type="hidden" name="payment_method" value="COD">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg">Place Order</button>
        </form>
    @else
        <p>Your cart is empty!</p>
        <a href="{{ route('market') }}" class="text-blue-600 hover:underline">Go back to marketplace</a>
    @endif
</div>
@endsection
