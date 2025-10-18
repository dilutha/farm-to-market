@extends('layouts.app')
@section('title', 'My Listings - Farm2Market')
@section('content')
<div class="container mx-auto px-6 py-8">
<h1 class="text-3xl font-bold mb-6">My Product Listings</h1>
<a href="{{ route('add-product') }}" class="inline-block mb-6 px-4 py-2
bg-green-600 text-white rounded hover:bg-green-700">Add New Listing</a>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
@forelse($crops as $crop)
<div class="bg-white shadow-md rounded p-4">
<img src="{{ asset('images/' . $crop->image) }}" alt="{{
$crop->crop_name }}" class="w-full h-40 object-cover rounded mb-4">
<h2 class="text-xl font-semibold">{{ $crop->crop_name
}}</h2>
<p class="text-gray-600">{{ $crop->description }}</p>
<p class="mt-2 font-semibold">Price: Rs. {{ $crop->price
}}/kg</p>
<p>Available: {{ $crop->quantity_available }} kg</p>
<p>Status: {{ $crop->status }}</p>
</div>
@empty
<p>No products found. Click "Add New Listing" to create your
first listing.</p>
@endforelse
</div>
</div>
@endsection