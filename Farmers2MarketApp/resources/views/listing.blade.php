@extends('layouts.app')

@section('title', 'Add New Crop - AgriConnect')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">

    <!-- Header -->
    <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-4 sm:px-6 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold mb-2">🌾 Manage Your Crops</h1>
                <p class="text-green-100 text-sm sm:text-base">Add and manage your agricultural products</p>
            </div>
            <a href="{{ route('farmer.dashboard') }}" class="hidden sm:flex items-center gap-2 bg-white text-green-600 hover:bg-green-50 px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                Back to Dashboard
            </a>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-8">

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <!-- Error Messages -->
        @if($errors->any())
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded-lg">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li class="text-red-700 dark:text-red-300">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Add Crop Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden mb-8">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-green-600 border-b">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">Add New Crop</h2>
            </div>

            <form action="{{ route('farmer.store-product') }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    <!-- Crop Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Crop Name <span class="text-red-500">*</span></label>
                        <input type="text" name="crop_name" value="{{ old('crop_name') }}" placeholder="e.g., Tomatoes" required
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <!-- Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Quantity Available (kg) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="quantity_available" value="{{ old('quantity_available') }}" required
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Price per kg (Rs.) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required
                               class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100">
                    </div>

                    <!-- Image -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Crop Image</label>
                        <input type="file" name="image" id="cropImage" accept="image/*" class="w-full">
                        <p id="fileName" class="mt-2 text-sm text-gray-600 dark:text-gray-400"></p>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-gray-100" placeholder="Describe your crop...">{{ old('description') }}</textarea>
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold shadow-md">Add Crop</button>
            </form>
        </div>

        <!-- Crop Listings -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">Your Listed Crops ({{ $crops->count() }})</h2>
            </div>

            @if($crops->count() > 0)
                <!-- Desktop Table -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Image</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($crops as $crop)
                            <tr>
                                <td class="px-6 py-4">
                                    <img src="{{ asset('uploads/crops/' . $crop->image) }}" alt="{{ $crop->crop_name }}" class="w-16 h-16 object-cover rounded-lg" onerror="this.src='{{ asset('uploads/crops/default_crop.jpg') }}'">
                                </td>
                                <td class="px-6 py-4">{{ $crop->crop_name }}</td>
                                <td class="px-6 py-4">{{ $crop->quantity_available }} kg</td>
                                <td class="px-6 py-4">Rs. {{ number_format($crop->price, 2) }}</td>
                                <td class="px-6 py-4">
                                    @if($crop->status == 'Approved')
                                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                                    @elseif($crop->status == 'Pending')
                                        <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @else
                                        <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards -->
                <div class="md:hidden p-4 space-y-4">
                    @foreach($crops as $crop)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 flex justify-between items-center">
                        <img src="{{ asset('uploads/crops/' . $crop->image) }}" class="w-20 h-20 object-cover rounded-lg" onerror="this.src='{{ asset('uploads/crops/default_crop.jpg') }}'">
                        <div class="flex-1 ml-4">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">{{ $crop->crop_name }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $crop->quantity_available }} kg</p>
                            <p class="text-lg font-bold text-green-600 dark:text-green-400">Rs. {{ number_format($crop->price, 2) }}</p>
                        </div>
                        @if($crop->status == 'Approved')
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Approved</span>
                        @elseif($crop->status == 'Pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Rejected</span>
                        @endif
                    </div>
                    @endforeach
                </div>

            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No crops listed yet</h3>
                    <p class="text-gray-600 dark:text-gray-400">Add your first crop using the form above</p>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
document.getElementById('cropImage').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name;
    document.getElementById('fileName').textContent = fileName ? '📎 Selected: ' + fileName : '';
});
</script>
@endsection
