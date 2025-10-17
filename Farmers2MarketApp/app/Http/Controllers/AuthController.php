<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('login');
    }

    /**
     * Handle login
     */
    public function login(Request $request)
    {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();
        $user = Auth::user();

        
        if ($user->role === 'Buyer') {
            return redirect()->route('buyer.dashboard')->with('success', 'Welcome back, ' . $user->name . '!');
        } elseif ($user->role === 'Farmer') {
            return redirect()->route('farmerdashboard'); // Create this route later
        } elseif ($user->role === 'Admin') {
            return redirect()->route('admindashboard'); // Create this route later
        }

        return redirect('/'); // fallback
    }

    return back()->withErrors([
        'email' => 'Invalid email or password.',
    ]);
}


    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        return view('register');
    }

    /**
     * Handle registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:Farmer,Buyer,Admin',
            'contact_no' => 'nullable|string',
           
            'location' => 'nullable|string',
            'farm_size' => 'nullable|numeric',
            'company_name' => 'nullable|string',
            'address' => 'nullable|string',
            'designation' => 'nullable|string',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => $validated['role'],
            'contact_no' => $validated['contact_no'] ?? null,
            'status' => 'Active',
        ]);

        // Automatically create profile based on role
        $user->createProfile($request->only([
            'location', 'farm_size', 'company_name', 'address', 'designation'
        ]));

        // Login the user immediately
        Auth::login($user);

        return redirect()->route('dashboard')->with('success', 'Account created successfully!');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
