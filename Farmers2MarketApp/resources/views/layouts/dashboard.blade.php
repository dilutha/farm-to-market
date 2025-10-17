<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>@yield('title', 'Dashboard - AgriConnect')</title>
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
        /* Dashboard Layout */
        .dashboard-container { display: grid; grid-template-columns: 250px 1fr; min-height: calc(100vh - 80px); gap: 1.5rem; padding: 1rem; }
        .dashboard-sidebar { background: var(--tw-bg-white); padding: 1.5rem; display:flex; flex-direction:column; gap:1rem; border-radius:1rem; height:calc(100vh - 80px); position:sticky; top:80px; }
        body.dark .dashboard-sidebar { background: #1a2e1b; }
        .nav-item { padding:0.5rem 1rem; border-radius:0.75rem; font-weight:500; transition:0.2s; display:block; color:inherit; }
        .nav-item:hover { background: #15931d20; }
        .nav-item.active { background:#15931d33; color:#15931d; }
        .dashboard-main { display:flex; flex-direction:column; gap:2rem; }
        .cards-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1.5rem; }
        .card { background: var(--tw-bg-white); padding:1.5rem; border-radius:1rem; box-shadow:0 10px 15px -3px rgba(0,0,0,0.1); }
        body.dark .card { background:#1a2e1b; }
        .status.pending { color:#f59e0b; font-weight:600; }
        .status.approved { color:#16a34a; font-weight:600; }
        .status.cancelled { color:#dc2626; font-weight:600; }
        table { width:100%; border-collapse:collapse; }
        th, td { text-align:left; padding:0.75rem; border-bottom:1px solid #e3e8e4; }
        body.dark th, body.dark td { border-color:#2a402b; }
    </style>
    @stack('styles')
</head>
<body class="bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">

<!-- Navbar -->
<header class="sticky top-0 z-50 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm border-b border-border-light dark:border-border-dark">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
        <a class="flex items-center gap-3" href="{{ route('home') }}">
            <svg class="h-8 w-8 text-primary" fill="currentColor" viewBox="0 0 24 24">
                <path clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10..." fill-rule="evenodd"></path>
            </svg>
            <span class="text-2xl font-bold">AgriConnect</span>
        </a>

        <div class="hidden md:flex items-center space-x-8">
            <a href="{{ route('home') }}" class="hover:text-primary transition">Home</a>
            <a href="{{ route('about') }}" class="hover:text-primary transition">About</a>
            <a href="{{ route('market') }}" class="hover:text-primary transition">Market</a>
            <a href="{{ route('contact') }}" class="hover:text-primary transition">Contact</a>
            <a href="{{ route('analysis') }}" class="hover:text-primary transition">Analysis</a>
        </div>

        <div class="hidden md:flex items-center space-x-4">
            @guest
                <a href="{{ route('login') }}" class="px-6 py-2 rounded-full border hover:bg-primary/10 transition font-semibold">Login</a>
                <a href="{{ route('register') }}" class="px-6 py-2 rounded-full text-white bg-primary hover:bg-opacity-90 transition font-semibold shadow">Sign Up</a>
            @else
                @php
                    $dashboardRoute = match(auth()->user()->role) {
                        'Buyer' => 'buyer.dashboard',
                        'Farmer' => 'farmer.dashboard',
                        'Admin' => 'admin.dashboard',
                        default => 'home',
                    };
                @endphp
                <a href="{{ route($dashboardRoute) }}" class="px-6 py-2 rounded-full border hover:bg-primary/10 transition font-semibold">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="px-6 py-2 rounded-full text-white bg-primary hover:bg-opacity-90 transition font-semibold shadow">Logout</button>
                </form>
            @endguest
        </div>
    </nav>
</header>

<!-- Dashboard Container -->
<div class="dashboard-container container mx-auto mt-4">
    <!-- Sidebar -->
    <aside class="dashboard-sidebar">
        <div class="font-bold text-xl">@yield('sidebar-title','Dashboard')</div>
        <nav class="flex flex-col gap-2 mt-4">
            @yield('sidebar')
        </nav>
        <div class="mt-auto">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item text-red-600">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="dashboard-main">
        @yield('content')
    </main>
</div>

<!-- Footer -->
<footer class="bg-card-light dark:bg-card-dark border-t border-border-light dark:border-border-dark py-6 text-center mt-6">
    <p class="text-sm text-text-light/60 dark:text-text-dark/60">© 2025 AgriConnect. All rights reserved.</p>
</footer>

@stack('scripts')
</body>
</html>
