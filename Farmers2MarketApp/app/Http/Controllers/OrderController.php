<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Crop;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Show checkout page with cart items
     */
    public function checkout()
    {
        $cart = session()->get('cart', []);
        return view('order_detail', compact('cart'));
    }

    /**
     * Place order (COD or PayHere)
     */
    public function placeOrder(Request $request)
    {
        $cartJson = $request->input('cart');
        $paymentMethod = $request->input('payment_method');
        $totalAmount = $request->input('total_amount');

        if (!$cartJson) {
            return redirect()->route('market')->with('error', 'Your cart is empty.');
        }

        $cart = json_decode($cartJson, true);
        if (!$cart || count($cart) === 0) {
            return redirect()->route('market')->with('error', 'Your cart is empty.');
        }

        // Create main order
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'payment_method' => $paymentMethod,
            'status' => $paymentMethod === 'COD' ? 'Pending' : 'Payment Pending',
        ]);

        // Save order items
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'crop_id' => $item['id'],
                'quantity' => $item['qty'] ?? $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        // Clear cart after placing order
        session()->forget('cart');

        if ($paymentMethod === 'COD') {
            return redirect()->route('order.success')->with('order', $order);
        }

        // Redirect to checkout page or payment gateway
        return redirect()->route('order.checkout')->with('order', $order);
    }

    /**
     * PayHere payment success page
     */
    public function success()
    {
        $order = session('order');
        if (!$order) {
            return redirect()->route('market')->with('error', 'No order found.');
        }

        $order->update(['status' => 'Paid']);

        return view('order_confirmation', compact('order'));
    }

    /**
     * PayHere payment canceled
     */
    public function cancel()
    {
        return redirect()->route('market')->with('error', 'Payment canceled.');
    }

    /**
     * PayHere payment notification webhook
     */
    public function notify(Request $request)
    {
        $orderId = $request->input('order_id');
        $paymentStatus = $request->input('status');

        $order = Order::find($orderId);
        if ($order && $paymentStatus === 'Paid') {
            $order->update(['status' => 'Paid']);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * Add single crop to session cart
     */
    public function addToCart(Request $request)
    {
        $crop = Crop::findOrFail($request->crop_id);
        $quantity = (int) $request->quantity;

        if ($quantity < 1) {
            return back()->with('error', 'Invalid quantity.');
        }

        $cart = session()->get('cart', []);

        $exists = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $crop->id) {
                $item['quantity'] += $quantity;
                $exists = true;
                break;
            }
        }
        unset($item);

        if (!$exists) {
            $cart[] = [
                'id' => $crop->id,
                'name' => $crop->crop_name,
                'price' => $crop->price,
                'quantity' => $quantity,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Item added to cart.');
    }
}
