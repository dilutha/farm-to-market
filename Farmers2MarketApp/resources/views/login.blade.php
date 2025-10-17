<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>AgriConnect - Login</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            "primary": "#2E7D32",
            "secondary": "#F9A825",
            "accent": "#6D4C41",
            "background": "#FFFCF5",
            "text-primary": "#1A202C",
            "text-secondary": "#4A5568",
            "surface": "#FFFFFF",
          },
          fontFamily: {
            "sans": ["Work Sans", "sans-serif"]
          },
          borderRadius: {
            "DEFAULT": "0.5rem",
            "lg": "0.75rem",
            "full": "9999px"
          },
          boxShadow: {
            "DEFAULT": "0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)",
            "lg": "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)"
          }
        },
      },
    }
</script>
</head>
<body class="bg-background font-sans text-text-primary">
<div class="flex flex-col min-h-screen">

  <!-- Header -->
  <header class="bg-surface shadow-md sticky top-0 z-10">
    <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
      <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary text-3xl">agriculture</span>
        <a class="text-2xl font-bold text-primary" href="#">AgriConnect</a>
      </div>
      <div class="hidden md:flex items-center space-x-8">
        <a class="hover:text-primary transition-colors" href="{{ route('home') }}">Home</a>
        <a class="hover:text-primary transition-colors" href="{{ route('about') }}">About</a>
        <a class="hover:text-primary transition-colors" href="{{ route('market') }}">Market</a>
        <a class="hover:text-primary transition-colors" href="{{ route('contact') }}">Contact</a>
        <a class="text-text-light dark:text-text-dark hover:text-primary dark:hover:text-primary transition-colors" href="{{ route('analysis') }}">Analysis</a>

    
      <button class="md:hidden flex items-center">
        <span class="material-symbols-outlined text-3xl">menu</span>
      </button>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="flex-grow flex items-center justify-center container mx-auto px-6 py-12">
    <section class="bg-surface rounded-lg shadow-lg w-full max-w-md p-8">
      <h1 class="text-3xl font-bold text-accent text-center mb-4">Welcome Back!</h1>
      <p class="text-text-secondary text-center mb-6">Sign in to access your account</p>

      @if($errors->any())
        <div class="text-red-600 text-sm mb-4 text-center">
          {{ $errors->first() }}
        </div>
      @endif

      <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
    @csrf

    <div class="flex justify-between mb-4">
        <label class="flex items-center gap-2">
            <input type="radio" name="role" value="Buyer" required class="accent-primary"/>
            Buyer
        </label>
        <label class="flex items-center gap-2">
            <input type="radio" name="role" value="Farmer" required class="accent-primary"/>
            Seller
        </label>
        <label class="flex items-center gap-2">
            <input type="radio" name="role" value="Admin" required class="accent-primary"/>
            Admin
        </label>
    </div>

    <div>
        <label for="email" class="block text-sm font-medium mb-1">Email</label>
        <input type="text" id="email" name="email" placeholder="Your email" required
            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary focus:border-primary"/>
    </div>

    <div>
        <label for="password" class="block text-sm font-medium mb-1">Password</label>
        <input type="password" id="password" name="password" placeholder="Your password" required
            class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-primary focus:border-primary"/>
    </div>

    <button type="submit"
        class="w-full py-3 px-4 bg-primary text-white font-semibold rounded-lg shadow hover:bg-primary/90 transition-colors">
        Sign In
    </button>
</form>


      <p class="text-center text-sm text-text-secondary mt-6">
        Don't have an account? <a href="#" class="text-primary font-medium hover:underline">Sign up now</a>
      </p>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-accent text-white mt-12">
    <div class="container mx-auto px-6 py-8 text-center">
      <p class="text-sm">© 2025 AgriConnect. All rights reserved.</p>
    </div>
  </footer>

</div>
</body>
</html>
