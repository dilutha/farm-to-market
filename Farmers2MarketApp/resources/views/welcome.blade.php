@extends('layouts.app')

@section('title', 'AgriConnect Sri Lanka')

@push('styles')
<style>
    .carousel-item { transition: opacity 0.6s ease-in-out; }
    .carousel-item-visible { opacity: 1; }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="relative h-[70vh] min-h-[500px] max-h-[700px] flex items-center justify-center text-center text-white overflow-hidden">
        <div class="absolute inset-0 z-0">
            <div id="hero-carousel" class="w-full h-full relative">
                <div class="carousel-item absolute inset-0 opacity-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-1.jpg') }}')"></div>
                <div class="carousel-item absolute inset-0 opacity-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-2.jpg') }}')"></div>
                <div class="carousel-item absolute inset-0 opacity-0 bg-cover bg-center" style="background-image: url('{{ asset('images/hero-3.jpg') }}')"></div>
            </div>
            <div class="absolute inset-0 bg-black/40 z-10"></div>
        </div>
        <div class="relative z-20 px-4 flex flex-col items-center justify-center h-full">
            <h1 class="text-4xl md:text-6xl font-bold mb-4 tracking-tight leading-tight">Discover Freshness. Empower Farmers.</h1>
            <p class="text-lg md:text-xl max-w-3xl mb-8 font-light">Your direct link to Sri Lanka's agricultural bounty. Support local farmers and enjoy fresh produce.</p>
            <form action="{{ route('market') }}" class="w-full max-w-xl flex">
                <input type="text" name="search" placeholder="Search for produce..." class="flex-grow px-6 py-3 rounded-full focus:outline-none focus:ring-2 focus:ring-primary bg-white dark:bg-card-dark"/>
                <button type="submit" class="ml-2 px-6 py-3 rounded-full bg-primary text-white font-semibold hover:bg-opacity-90 transition-colors">Search</button>
            </form>
        </div>
    </section>

    <!-- Mission Section -->
    <section class="py-16 md:py-24">
        <div class="container mx-auto px-6 text-center max-w-3xl">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Our Mission</h2>
            <p class="text-lg text-text-light/80 dark:text-text-dark/80">
                Empower Sri Lankan farmers via direct sales, AI-powered crop pricing, and a sustainable agricultural ecosystem.
            </p>
        </div>
    </section>

    <!-- How It Works -->
    <section class="py-16 md:py-24 bg-primary/5 dark:bg-primary/10">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">How It Works</h2>
            <p class="text-lg mb-12 text-text-light/80 dark:text-text-dark/80">Simple, transparent process from farm to table.</p>
            <div class="grid md:grid-cols-3 gap-8 text-center">
                <div class="flex flex-col items-center p-6 bg-card-light dark:bg-card-dark rounded-lg shadow-lg">
                    <div class="p-4 bg-accent-yellow rounded-full mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h7.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">1. Farmers List Produce</h3>
                    <p class="text-text-light/80 dark:text-text-dark/80">Farmers upload harvests with images, description, and pricing.</p>
                </div>
                <div class="flex flex-col items-center p-6 bg-card-light dark:bg-card-dark rounded-lg shadow-lg">
                    <div class="p-4 bg-accent-brown rounded-full mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.658-.463 1.243-1.117 1.243H4.252c-.654 0-1.187-.585-1.117-1.243l1.263-12A3.75 3.75 0 0 1 7.5 4.5h9a3.75 3.75 0 0 1 3.406 4.007Z" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">2. Buyers Browse & Buy</h3>
                    <p class="text-text-light/80 dark:text-text-dark/80">Explore a wide variety of local products and make secure purchases online.</p>
                </div>
                <div class="flex flex-col items-center p-6 bg-card-light dark:bg-card-dark rounded-lg shadow-lg">
                    <div class="p-4 bg-primary rounded-full mb-4">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.125-.504 1.125-1.125V14.25m-17.25 4.5h10.5m-10.5-4.5v-3.375c0-.621.504-1.125 1.125-1.125h14.25c.621 0 1.125.504 1.125 1.125v3.375m-16.5-9L12 3m0 0 4.5 4.5M12 3v13.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">3. Direct Delivery</h3>
                    <p class="text-text-light/80 dark:text-text-dark/80">Fresh produce delivered straight from the farm to your doorstep.</p>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Carousel
    const carousel = document.getElementById('hero-carousel');
    const items = carousel.querySelectorAll('.carousel-item');
    let currentIndex = 0;
    const intervalTime = 5000;

    function showSlide(index) {
        items.forEach((item,i) => {
            item.classList.toggle('carousel-item-visible', i === index);
            item.classList.toggle('opacity-0', i !== index);
        });
    }
    setInterval(() => { currentIndex = (currentIndex+1)%items.length; showSlide(currentIndex); }, intervalTime);
    showSlide(currentIndex);

    // Mobile Menu Toggle
    const menuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if(menuBtn){
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>
@endpush
