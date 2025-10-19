<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ListingController extends Controller
{
    // Show farmer dashboard / listing page
    public function index()
    {
        $user = Auth::user();

        // Ensure the user has a farmer profile
        if (!$user->farmerProfile) {
            return redirect()->back()->with('error', 'No farmer profile found.');
        }

        $farmerId = $user->farmerProfile->farmer_id;
        $crops = Crop::where('farmer_id', $farmerId)->latest()->get();

        return view('listing', compact('crops'));
    }

    // Store new crop
    public function storeCrop(Request $request)
    {
        $request->validate([
            'crop_name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'quantity_available' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        if (!$user->farmerProfile) {
            return redirect()->back()->with('error', 'No farmer profile found.');
        }

        $farmerId = $user->farmerProfile->farmer_id;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = Str::slug($request->crop_name) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/crops'), $filename);
        } else {
            $filename = 'Farmers2MarketApp/public/storage/crops/default_crop.jpg';
        }

        // Create crop
        Crop::create([
            'farmer_id' => $farmerId,
            'crop_name' => $request->crop_name,
            'description' => $request->description,
            'quantity_available' => $request->quantity_available,
            'price' => $request->price,
            'image' => $filename,
            'status' => 'Pending',
        ]);

        return redirect()->route('farmer.listing')
                         ->with('success', 'Crop added successfully! It will appear in the marketplace once approved.');
    }
}
