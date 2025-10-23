<?php 
 
namespace App\Http\Controllers; 
 
use Illuminate\Http\Request; 
use App\Models\Seller; 
use App\Models\Product; 
use App\Models\Order; 
 
class SellerController extends Controller 
{ 
    // Show Seller Dashboard 
    public function dashboard() 
    { 
        $seller = auth()->user(); // Assuming seller is logged in 
        $products = Product::where('seller_id', $seller->id)->get(); 
        $orders = Order::whereHas('product', function($q) use ($seller) { 
            $q->where('seller_id', $seller->id); 
        })->get(); 
 
        $totalRevenue = $orders->sum('total_price'); 
 
        return view('seller.dashboard', compact('seller', 'products', 
'orders', 'totalRevenue')); 
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