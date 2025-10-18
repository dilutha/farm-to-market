<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BuyerProfile;
use App\Models\Order;

class BuyerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $buyerProfile = BuyerProfile::where('buyer_id', $user->user_id)->firstOrNew([
            'buyer_id' => $user->user_id,
        ]);

        $orders = Order::with(['items.crop', 'payment'])
            ->where('buyer_id', $user->user_id)
            ->orderByDesc('created_at')
            ->get();

        return view('buyer.dashboard', compact('user', 'buyerProfile', 'orders'));
    }

    public function updateAddress(Request $request)
    {
        $request->validate(['address' => 'required|string|max:255']);
        $user = Auth::user();

        $buyerProfile = BuyerProfile::firstOrNew(['buyer_id' => $user->user_id]);
        $buyerProfile->address = $request->address;
        $buyerProfile->save();

        return redirect()->back()->with('success', 'Address updated successfully!');
    }
}
