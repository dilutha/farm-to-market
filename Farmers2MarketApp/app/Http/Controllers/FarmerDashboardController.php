<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Crop;
use App\Models\Order;

class FarmerDashboardController extends Controller
{
    // Show Farmer Dashboard
    public function index()
{
    $farmer = auth()->user(); // Logged-in user

    if (!$farmer->farmerProfile) {
        return redirect()->back()->with('error', 'No farmer profile found.');
    }

    $farmerId = $farmer->farmerProfile->farmer_id;

    // Fetch crops for this farmer
    $crops = Crop::where('farmer_id', $farmerId)->get();

    // Fetch orders that include this farmer's crops
    $orders = Order::whereHas('orderItems.crop', function($query) use ($farmerId) {
        $query->where('farmer_id', $farmerId);
    })->with(['orderItems.crop'])->get();

    // Calculate total revenue
    $totalRevenue = 0;
    foreach ($orders as $order) {
        foreach ($order->orderItems as $item) {
            if ($item->crop && $item->crop->farmer_id == $farmerId) {
                $totalRevenue += $item->quantity * $item->unit_price;
            }
        }
    }

    return view('farmer.dashboard', compact('farmer', 'crops', 'orders', 'totalRevenue'));
}

    // Change Order Status
    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status updated!');
    }

    // Add new crop
    public function createProduct(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
        ]);

        Crop::create([
            'farmer_id' => auth()->user()->id,
            'crop_name' => $request->name,
            'quantity_available' => $request->quantity,
            'price' => $request->price,
            'status' => 'Pending', // default status
        ]);

        return redirect()->back()->with('success', 'Crop added successfully!');
    }
}