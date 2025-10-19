<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>AgriConnect - Register</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@400;500;600;700&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet"/>
<script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    "primary": "#2E7D32",
                    "accent": "#6D4C41",
                    "background": "#FFFCF5",
                    "text-primary": "#1A202C",
                    "text-secondary": "#4A5568",
                    "surface": "#FFFFFF",
                },
                fontFamily: { "sans": ["Work Sans", "sans-serif"] },
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
        <a class="text-2xl font-bold text-primary" href="{{ route('home') }}">AgriConnect</a>
      </div>
      <div class="hidden md:flex items-center space-x-8">
        <a href="{{ route('home') }}" class="hover:text-primary">Home</a>
        <a href="{{ route('about') }}" class="hover:text-primary">About</a>
        <a href="{{ route('market') }}" class="hover:text-primary">Market</a>
        <a href="{{ route('contact') }}" class="hover:text-primary">Contact</a>
        <a href="{{ route('analysis') }}" class="hover:text-primary">Analysis</a>
      </div>
      <button class="md:hidden flex items-center">
        <span class="material-symbols-outlined text-3xl">menu</span>
      </button>
    </nav>
  </header>

  <!-- Main Content -->
  <main class="flex-grow flex items-center justify-center container mx-auto px-6 py-12">
    <section class="bg-surface rounded-lg shadow-lg w-full max-w-md p-8">
      <h1 class="text-3xl font-bold text-accent text-center mb-4">Create Account</h1>
      <p class="text-text-secondary text-center mb-6">Register as a Farmer or Buyer</p>

      @if($errors->any())
        <div class="text-red-600 text-sm mb-4 text-center">{{ $errors->first() }}</div>
      @endif

      <form action="{{ route('register.submit') }}" method="POST" class="space-y-4">
        @csrf

        <!-- Role Selection -->
        <div class="flex justify-between mb-4">
          <label class="flex items-center gap-2">
            <input type="radio" name="role" value="Buyer" checked required class="accent-primary"/>
            Buyer
          </label>
          <label class="flex items-center gap-2">
            <input type="radio" name="role" value="Farmer" required class="accent-primary"/>
            Farmer
          </label>
        </div>

        <!-- Common Fields -->
        <input type="text" name="name" placeholder="Full Name" required class="w-full p-3 border rounded"/>
        <input type="email" name="email" placeholder="Email" required class="w-full p-3 border rounded"/>
        <input type="text" name="contact_no" placeholder="Contact Number" class="w-full p-3 border rounded"/>
        <input type="password" name="password" placeholder="Password" required class="w-full p-3 border rounded"/>
        <input type="password" name="password_confirmation" placeholder="Confirm Password" required class="w-full p-3 border rounded"/>

        <!-- Farmer Fields -->
        <div id="farmer-fields" class="hidden space-y-2">
          <input type="text" name="location" placeholder="Location" class="w-full p-3 border rounded"/>
          <input type="number" name="farm_size" placeholder="Farm Size" class="w-full p-3 border rounded"/>
        </div>

        <!-- Buyer Fields -->
        <div id="buyer-fields" class="space-y-2">
          <input type="text" name="company_name" placeholder="Company Name" class="w-full p-3 border rounded"/>
          <input type="text" name="address" placeholder="Address" class="w-full p-3 border rounded"/>
          <input type="text" name="designation" placeholder="Designation" class="w-full p-3 border rounded"/>
        </div>

        <button type="submit" class="w-full py-3 bg-primary text-white font-semibold rounded hover:bg-primary/90 transition">Register</button>
      </form>

      <p class="text-center text-sm text-text-secondary mt-6">
        Already have an account? <a href="{{ route('login') }}" class="text-primary font-medium hover:underline">Sign in</a>
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

<script>
const buyerRadio = document.querySelector('input[value="Buyer"]');
const farmerRadio = document.querySelector('input[value="Farmer"]');
const farmerFields = document.getElementById('farmer-fields');
const buyerFields = document.getElementById('buyer-fields');

function toggleFields() {
    if(farmerRadio.checked){
        farmerFields.style.display = 'block';
        buyerFields.style.display = 'none';
    } else {
        buyerFields.style.display = 'block';
        farmerFields.style.display = 'none';
    }
}

buyerRadio.addEventListener('change', toggleFields);
farmerRadio.addEventListener('change', toggleFields);

// Initialize
toggleFields();
</script>
</body>
</html>
