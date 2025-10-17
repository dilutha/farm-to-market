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
        <h3>Orders</h3>
        <table class="w-full border-collapse">
            <thead>
                <tr>
                    <th>Crop</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->crop?->crop_name ?? 'N/A' }}</td>
                    <td>{{ $order->quantity }}</td>
                    <td>{{ $order->total_price }}</td>
                    <td class="status {{ strtolower($order->status) }}">{{ $order->status }}</td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                </tr>
                @empty
                <tr><td colspan="5">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
