<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BuyerProfile;
use App\Models\Order;
use App\Models\Crop;

class BuyerDashboardController extends Controller
{
    /**
     * Display the buyer dashboard
     */
    public function index()
    {
        $user = Auth::user();

        // Fetch buyer profile
        $buyerProfile = BuyerProfile::where('buyer_id', $user->user_id)->first();

        // If no profile exists, create a placeholder object
        if (!$buyerProfile) {
            $buyerProfile = new BuyerProfile([
                'company_name' => null,
                'address' => null,
                'verification_status' => 'Pending',
                'status' => 'Inactive',
            ]);
        }

        // Fetch orders for this buyer with crop details
        $orders = Order::with('crop')
            ->where('buyer_id', $user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyer.dashboard', compact('user', 'buyerProfile', 'orders'));
    }

    /**
     * Update buyer address
     */
    public function updateAddress(Request $request)
    {
        $request->validate([
            'address' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Fetch or create buyer profile
        $buyerProfile = BuyerProfile::firstOrNew(['buyer_id' => $user->user_id]);
        $buyerProfile->address = $request->address;
        $buyerProfile->save();

        return redirect()->back()->with('success', 'Address updated successfully!');
    }
}
