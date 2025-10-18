@extends('layouts.dashboard')
@section('title', 'Buyer Dashboard - AgriConnect')
@section('sidebar-title', 'Buyer Dashboard')

@section('sidebar')
<a href="#profile" class="nav-item active">Profile Overview</a>
<a href="#orders" class="nav-item">Orders</a>
<a href="#address" class="nav-item">Change Address</a>
<a href="{{ route('market') }}" class="nav-item">Browse Crop</a>
@endsection

@section('content')
<div>
    <h1 class="text-2xl font-bold">Hello, {{ $user->name ?? 'Buyer' }}</h1>
    
    <!-- Profile Cards -->
    <div class="cards-grid mt-4" id="profile">
        <div class="card">
            <h3>Profile Info</h3>
            <p><strong>Company:</strong> {{ $buyerProfile?->company_name ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Contact No:</strong> {{ $user->contact_no }}</p>
            <p><strong>Address:</strong> {{ $buyerProfile?->address ?? 'N/A' }}</p>
        </div>
        <div class="card">
            <h3>Verification Status</h3>
            <p class="status {{ strtolower($buyerProfile?->verification_status ?? 'pending') }}">
                {{ $buyerProfile?->verification_status ?? 'Pending' }}
            </p>
        </div>
    </div>
    
    <!-- Orders Table -->
    <div class="card mt-6" id="orders">
        <h3>My Orders</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Items</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->order_id }}</td>
                    <td>
                        @foreach($order->orderItems as $item)
                            <div>{{ $item->crop->crop_name }} ({{ $item->quantity }}kg @ Rs.{{ number_format($item->unit_price, 2) }})</div>
                        @endforeach
                    </td>
                    <td>Rs. {{ number_format($order->total_price, 2) }}</td>
                    <td>{{ $order->payment?->payment_method ?? 'N/A' }}</td>
                    <td class="status {{ strtolower($order->status) }}">{{ $order->status }}</td>
                    <td>{{ $order->created_at ? $order->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">No orders found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Change Address Form -->
    <div class="card mt-6" id="address">
        <h3>Update Delivery Address</h3>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('buyer.update.address') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium mb-2">Delivery Address</label>
                <textarea 
                    name="address" 
                    id="address" 
                    rows="3" 
                    class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                    required
                >{{ old('address', $buyerProfile?->address) }}</textarea>
                @error('address')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                Update Address
            </button>
        </form>
    </div>
</div>
@endsection