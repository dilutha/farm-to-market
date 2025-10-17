{{-- resources/views/contact.blade.php --}}
@extends('layouts.app')

@section('title', 'AgriConnect - Contact Us')

@section('content')
<main class="flex-grow container mx-auto px-6 py-12">
    <section class="text-center mb-16">
        <h1 class="text-5xl font-bold text-accent mb-4">Contact Us</h1>
        <p class="text-text-secondary text-lg max-w-3xl mx-auto mb-8">
            We'd love to hear from you! Whether you have questions about our platform, services, or anything else, our team is ready to respond.
        </p>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6 max-w-xl mx-auto">
            {{ session('success') }}
        </div>
        @endif
    </section>

    <section class="grid md:grid-cols-2 gap-12 items-start">
        <!-- Contact Info -->
        <div class="space-y-6">
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

        <!-- Contact Form -->
        <div class="bg-surface rounded-lg shadow-md p-8">
            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-text-primary">Full Name</label>
                    <input type="text" name="name" id="name" placeholder="Your Name" required
                        class="mt-1 block w-full px-4 py-3 bg-background border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" />
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-text-primary">Email Address</label>
                    <input type="email" name="email" id="email" placeholder="you@example.com" required
                        class="mt-1 block w-full px-4 py-3 bg-background border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" />
                </div>
                <div>
                    <label for="subject" class="block text-sm font-medium text-text-primary">Subject</label>
                    <input type="text" name="subject" id="subject" placeholder="Subject (optional)"
                        class="mt-1 block w-full px-4 py-3 bg-background border border-gray-300 rounded-lg focus:ring-primary focus:border-primary" />
                </div>
                <div>
                    <label for="message" class="block text-sm font-medium text-text-primary">Message</label>
                    <textarea name="message" id="message" rows="5" placeholder="Your message..." required
                        class="mt-1 block w-full px-4 py-3 bg-background border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"></textarea>
                </div>
                <div>
                    <button type="submit"
                        class="w-full py-3 px-4 rounded-lg text-white bg-primary hover:bg-primary/90 font-semibold shadow">
                        Send Message
                    </button>
                </div>
            </form>
        </div>
    </section>
</main>
@endsection
