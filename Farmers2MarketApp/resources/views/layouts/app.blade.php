<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'AgriConnect Sri Lanka')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com"/>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#15931d",
                        "background-light": "#f6f8f6",
                        "background-dark": "#112112",
                        "text-light": "#112112",
                        "text-dark": "#f6f8f6",
                        "card-light": "#ffffff",
                        "card-dark": "#1a2e1b",
                        "border-light": "#e3e8e4",
                        "border-dark": "#2a402b",
                    },
                    fontFamily: { display: ["Work Sans", "sans-serif"] },
                    borderRadius: { xl: "1rem", full: "9999px" },
                    boxShadow: {
                        lg: "0 10px 15px -3px rgba(0,0,0,0.1),0 4px 6px -4px rgba(0,0,0,0.1)"
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Work Sans', sans-serif; }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
<div class="flex flex-col min-h-screen">
    <!-- Navbar -->
    <header class="sticky top-0 z-50 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-border-light dark:border-border-dark">
        <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
            <!-- Logo -->
            <a class="flex items-center gap-3" href="{{ route('home') }}">
                <svg class="h-6 w-6 text-primary flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                    <path clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10..." fill-rule="evenodd"></path>
                </svg>
                <span class="text-2xl font-bold leading-none">AgriConnect</span>
            </a>

            <!-- Desktop Menu -->
            <div class="hidden md:flex items-center space-x-8">
                <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
                <a href="{{ route('about') }}" class="hover:text-primary transition">About</a>
                <a href="{{ route('market') }}" class="hover:text-primary transition">Market</a>
                <a href="{{ route('contact') }}" class="hover:text-primary transition">Contact</a>
                <a href="{{ route('analysis') }}" class="hover:text-primary transition">Analysis</a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center space-x-4">
                @guest
                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-full border hover:bg-primary/10 transition font-semibold">Login</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 rounded-full text-white bg-primary hover:bg-opacity-90 transition font-semibold shadow">Sign Up</a>
                @else
                    @php
                        $dashboardRoute = match(auth()->user()->role) {
                            'Buyer' => 'buyer.dashboard',
                            'Farmer' => 'farmer.dashboard',
                            'Admin' => 'admin.dashboard',
                            default => 'home',
                        };
                    @endphp
                    <a href="{{ route($dashboardRoute) }}" class="px-5 py-2 rounded-full border hover:bg-primary/10 transition font-semibold">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-5 py-2 rounded-full text-white bg-primary hover:bg-opacity-90 transition font-semibold shadow">Logout</button>
                    </form>
                @endguest
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-button" class="md:hidden p-2 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </nav>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-background-light dark:bg-background-dark border-t border-border-light dark:border-border-dark">
            <a href="{{ route('home') }}" class="block px-6 py-3 hover:bg-primary/10 transition">Home</a>
            <a href="{{ route('about') }}" class="block px-6 py-3 hover:bg-primary/10 transition">About</a>
            <a href="{{ route('market') }}" class="block px-6 py-3 hover:bg-primary/10 transition">Market</a>
            <a href="{{ route('contact') }}" class="block px-6 py-3 hover:bg-primary/10 transition">Contact</a>
            <a href="{{ route('analysis') }}" class="block px-6 py-3 hover:bg-primary/10 transition">Analysis</a>
            @guest
                <a href="{{ route('login') }}" class="block px-6 py-3 hover:bg-primary/10 transition">Login</a>
                <a href="{{ route('register') }}" class="block px-6 py-3 text-white bg-primary hover:bg-opacity-90 transition text-center">Sign Up</a>
            @else
                <a href="{{ route($dashboardRoute) }}" class="block px-6 py-3 hover:bg-primary/10 transition">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full px-6 py-3 text-white bg-primary hover:bg-opacity-90 transition">Logout</button>
                </form>
            @endguest
        </div>
    </header>

    <!-- Content Section -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-card-light dark:bg-card-dark border-t border-border-light dark:border-border-dark py-6 text-center">
        <p class="text-sm text-text-light/60 dark:text-text-dark/60">© 2025 AgriConnect. All rights reserved.</p>
    </footer>
</div>

@stack('scripts')
<script>
    // Mobile Menu Toggle
    const menuBtn = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if(menuBtn){
        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>
</body>
</html>
