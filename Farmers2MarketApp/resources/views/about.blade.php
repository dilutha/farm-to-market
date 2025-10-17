@extends('layouts.app')

@section('title', 'About Us - AgriConnect')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
<style>
    .stat-number {
        transition: transform 0.3s ease-out, color 0.3s ease-out;
    }
    .stat-card:hover .stat-number {
        transform: translateY(-5px) scale(1.1);
        color: var(--tw-color-primary);
    }
    .stat-card:hover .stat-label {
        color: var(--tw-color-primary);
    }
    .stat-icon {
        transition: transform 0.3s ease-out, color 0.3s ease-out;
    }
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
        color: var(--tw-color-secondary);
    }
</style>
@endpush

@section('content')
<main class="flex-grow container mx-auto px-6 py-12 bg-background font-sans text-text-primary">

    <!-- Intro Section -->
    <section class="text-center mb-16">
        <h1 class="text-5xl font-bold text-accent mb-4">About AgriConnect</h1>
        <p class="text-text-secondary text-lg max-w-3xl mx-auto">
            A pioneering platform in Sri Lanka, dedicated to transforming the agricultural landscape
            by directly connecting local farmers with consumers and businesses. We are committed to
            fostering a sustainable and equitable food system.
        </p>
    </section>

    <!-- Mission & Vision Section -->
    <section class="mb-16">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-4xl font-bold text-accent mb-4">Our Mission & Vision</h2>
                <p class="text-text-secondary mb-4">
                    Our mission is to empower farmers by providing them with a transparent, efficient,
                    and profitable marketplace. We aim to enhance their livelihoods and ensure consumers
                    have access to fresh, high-quality produce at fair prices.
                </p>
                <p class="text-text-secondary">
                    We envision a future where technology bridges the gap between the farm and the table,
                    minimizing waste, optimizing the supply chain, and promoting food security for all
                    in Sri Lanka.
                </p>
            </div>
            <div>
                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD3KFg8BuQjpOGXLkd4Sjkx0gc8UhMt9rJPH3-vkUFHF7f_HZFhBPYmH8DnSt2f-0SluNWdxCrIcngcL4SI1x9zVtUwlkO3v-081kjGCIlvwjxULkhu0YZLIPEQWeu6VadUx-NjOMcmcIpK5Mf81yXoRFcYpef5cC8Fi7-_3wvASik1Yq7Io092vccxAvKXu6AHgfjf1iLX3BQPTqQs8whA98MTKFWHpV9CD1uGcDfs-WdAOirHElu004G9-8w7IX9Hlin_Rfvxtx68"
                     alt="Farmer in a field"
                     class="rounded-lg shadow-lg w-full h-auto object-cover">
            </div>
        </div>
    </section>

    <!-- Impact Section -->
    <section class="bg-surface rounded-lg shadow-lg p-8 sm:p-12 mb-16">
        <h2 class="text-4xl font-bold text-accent text-center mb-8">Our Socio-Economic Impact</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8 text-center">
            <div class="stat-card p-6 cursor-pointer">
                <span class="material-symbols-outlined text-secondary text-5xl mb-4 stat-icon">groups</span>
                <h3 class="text-2xl font-bold text-primary mb-2 stat-number" data-target="5000">0</h3>
                <p class="text-text-secondary stat-label">Farmers Empowered</p>
            </div>
            <div class="stat-card p-6 cursor-pointer">
                <span class="material-symbols-outlined text-secondary text-5xl mb-4 stat-icon">sync_alt</span>
                <h3 class="text-2xl font-bold text-primary mb-2 stat-number" data-target="10000">0</h3>
                <p class="text-text-secondary stat-label">Successful Transactions</p>
            </div>
            <div class="stat-card p-6 cursor-pointer">
                <span class="material-symbols-outlined text-secondary text-5xl mb-4 stat-icon">recycling</span>
                <h3 class="text-2xl font-bold text-primary mb-2 stat-number" data-target="20">0%</h3>
                <p class="text-text-secondary stat-label">Reduction in Post-Harvest Waste</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="bg-primary/10 rounded-lg shadow-lg p-8 sm:p-12" id="contact">
        <div class="grid md:grid-cols-2 gap-12">
            <div>
                <h2 class="text-4xl font-bold text-accent mb-4">Get In Touch</h2>
                <p class="text-text-secondary mb-8">
                    We'd love to hear from you! Whether you have a question about our platform,
                    services, or anything else, our team is ready to answer all your questions.
                </p>
                <div class="space-y-4">
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl">location_on</span>
                        <p class="text-text-secondary">Colombo, Sri Lanka</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl">mail</span>
                        <p class="text-text-secondary">contact@agriconnect.lk</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="material-symbols-outlined text-primary text-3xl">call</span>
                        <p class="text-text-secondary">+94 11 234 5678</p>
                    </div>
                </div>
            </div>

            <div class="bg-surface rounded-lg shadow-md p-8">
                <form action="#" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-medium text-text-primary">Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Your Name"
                               class="mt-1 block w-full px-4 py-3 bg-background border border-gray-300 rounded-lg
                                      shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-text-primary">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="you@example.com"
                               class="mt-1 block w-full px-4 py-3 bg-background border border-gray-300 rounded-lg
                                      shadow-sm focus:outline-none focus:ring-primary focus:border-primary">
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-medium text-text-primary">Message</label>
                        <textarea id="message" name="message" rows="4" placeholder="Your message..."
                                  class="mt-1 block w-full px-4 py-3 bg-background border border-gray-300 rounded-lg
                                         shadow-sm focus:outline-none focus:ring-primary focus:border-primary"></textarea>
                    </div>
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm
                                       text-white bg-primary hover:bg-primary/90 focus:outline-none
                                       focus:ring-2 focus:ring-offset-2 focus:ring-primary font-semibold">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.stat-number');
    const observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                counters.forEach(counter => {
                    const target = +counter.dataset.target;
                    let current = 0;
                    const increment = target / 100;
                    const update = () => {
                        if (current < target) {
                            current += increment;
                            counter.textContent = Math.ceil(current) + (counter.dataset.target === "20" ? "%" : "");
                            requestAnimationFrame(update);
                        } else {
                            counter.textContent = target + (counter.dataset.target === "20" ? "%" : "");
                        }
                    };
                    update();
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

    counters.forEach(counter => observer.observe(counter.closest('.stat-card')));
});
</script>
@endpush
