<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Show forms
    public function showRegisterForm()
    {
        return view('register'); // radio buttons: Farmer, Buyer
    }

    public function showLoginForm()
    {
        return view('login');
    }

    // Register
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

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $validated['role'],
            'contact_no' => $validated['contact_no'] ?? null,
            'status' => 'Active',
        ]);

        // Create role profile
        $user->createProfile($validated);

        // Login and redirect
        Auth::login($user);

        return match($user->role) {
            'Farmer' => redirect()->route('farmer.dashboard')->with('success', 'Welcome, '.$user->name),
            'Buyer'  => redirect()->route('buyer.dashboard')->with('success', 'Welcome, '.$user->name),
            'Admin'  => redirect()->route('admin.dashboard')->with('success', 'Welcome, '.$user->name),
        };
    }

    // Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        if(Auth::attempt($credentials)){
            $request->session()->regenerate();
            $user = Auth::user();
            return match($user->role) {
                'Farmer' => redirect()->route('farmer.dashboard')->with('success', 'Welcome back, '.$user->name),
                'Buyer'  => redirect()->route('buyer.dashboard')->with('success', 'Welcome back, '.$user->name),
                'Admin'  => redirect()->route('admin.dashboard')->with('success', 'Welcome back, '.$user->name),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success','Logged out successfully.');
    }
}