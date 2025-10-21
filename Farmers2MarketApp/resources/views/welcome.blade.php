@extends('layouts.app')

@section('title', 'AgriConnect Sri Lanka')

@push('styles')
<style>
    .carousel-item { 
        opacity: 0; 
        transition: opacity 0.8s ease-in-out; 
    }
    .carousel-item-visible { opacity: 1; }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<!-- Hero Section -->
<section class="relative h-[70vh] flex items-center justify-center text-center text-white overflow-hidden">
    <!-- Carousel Background -->
    <div class="absolute inset-0">
        <div id="hero-carousel" class="w-full h-full relative">
            <div class="carousel-item absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-1.jpg') }}')"></div>
            <div class="carousel-item absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-2.jpg') }}')"></div>
            <div class="carousel-item absolute inset-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-3.jpg') }}')"></div>
        </div>
        <div class="absolute inset-0 bg-black/40"></div>
    </div>

    <!-- Full-size Search Background Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('storage/Untitled design(2).png') }}" 
             alt="Background" 
             class="w-full h-full object-cover opacity-30 pointer-events-none">
    </div>

    <!-- Content -->
    <div class="relative z-10 px-4">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Discover Freshness. Empower Farmers.</h1>
        <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto font-light">
            Your direct link to Sri Lanka’s agricultural bounty — connecting farmers and buyers transparently.
        </p>
        <form action="{{ route('market') }}" class="flex justify-center max-w-xl mx-auto relative">
            <input type="text" name="search" placeholder="Search for produce..." 
                class="flex-grow px-6 py-3 rounded-full focus:ring-2 focus:ring-primary bg-white dark:bg-card-dark text-gray-800 dark:text-white relative z-20"/>
            <button type="submit" 
                class="ml-2 px-6 py-3 rounded-full bg-primary text-white font-semibold hover:bg-opacity-90 transition-colors relative z-20">
                Search
            </button>
        </form>
    </div>
</section>


<!-- Mission Section -->
<section class="py-16 text-center">
    <div class="container mx-auto px-6 max-w-3xl">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Mission</h2>
        <p class="text-lg text-text-light/80 dark:text-text-dark/80">
            To build a sustainable bridge between Sri Lankan farmers and consumers using technology — ensuring fair prices, minimal waste, and quality produce.
        </p>
    </div>
</section>

<!-- How It Works -->
<section class="py-16 bg-primary/5 dark:bg-primary/10">
    <div class="container mx-auto px-6 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">How It Works</h2>
        <p class="text-lg mb-12 text-text-light/80 dark:text-text-dark/80">From farm to table in three easy steps.</p>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="flex flex-col items-center p-6 bg-card-light dark:bg-card-dark rounded-xl shadow-lg">
                <div class="p-4 bg-primary rounded-full mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m2.25 12 8.954-8.955a1.125 1.125 0 0 1 1.591 0L21.75 12M4.5 9.75v10.125A1.125 1.125 0 0 0 5.625 21H9.75v-4.875a1.125 1.125 0 0 1 1.125-1.125h2.25A1.125 1.125 0 0 1 14.25 16.125V21h4.125a1.125 1.125 0 0 0 1.125-1.125V9.75" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">1. Farmers List Produce</h3>
                <p class="text-text-light/80 dark:text-text-dark/80">Upload fresh harvests with images, descriptions, and fair pricing.</p>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center p-6 bg-card-light dark:bg-card-dark rounded-xl shadow-lg">
                <div class="p-4 bg-primary rounded-full mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12a1.125 1.125 0 0 1-1.117 1.243H4.252a1.125 1.125 0 0 1-1.117-1.243l1.263-12A3.75 3.75 0 0 1 7.5 4.5h9a3.75 3.75 0 0 1 3.406 4.007Z" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">2. Buyers Browse & Buy</h3>
                <p class="text-text-light/80 dark:text-text-dark/80">Shop securely, directly from trusted local farmers with verified ratings.</p>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center p-6 bg-card-light dark:bg-card-dark rounded-xl shadow-lg">
                <div class="p-4 bg-primary rounded-full mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375A1.125 1.125 0 0 1 2.25 17.625V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125A1.125 1.125 0 0 0 22.5 17.625V14.25M5.25 10.5h13.5M12 3l4.5 4.5M12 3 7.5 7.5" />
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">3. Direct Delivery</h3>
                <p class="text-text-light/80 dark:text-text-dark/80">Fresh produce delivered directly from farms to your doorstep — fast and traceable.</p>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
const items = document.querySelectorAll('.carousel-item');
let currentIndex = 0;
const intervalTime = 5000;

function showSlide(index) {
    items.forEach((item, i) => {
        item.classList.toggle('carousel-item-visible', i === index);
    });
}
showSlide(currentIndex);
setInterval(() => {
    currentIndex = (currentIndex + 1) % items.length;
    showSlide(currentIndex);
}, intervalTime);
</script>
@endpush
