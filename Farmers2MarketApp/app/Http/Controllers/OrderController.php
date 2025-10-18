<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Crop;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Show checkout page
     */
    public function checkout(Request $request)
    {
        if ($request->isMethod('post')) {
            $cart = json_decode($request->input('cart'), true);
            
            if (!$cart || !is_array($cart)) {
                return redirect()->route('market')->with('error', 'Invalid cart data.');
            }
            
            session(['cart' => $cart]);
            Log::info('Cart stored in session:', ['cart' => $cart]);
        }
        
        $cart = session('cart', []);
        
        if (empty($cart)) {
            return redirect()->route('market')->with('error', 'Your cart is empty.');
        }
        
        return view('orderdetail');
    }

    /**
     * Place order (COD or PayHere)
     */
    public function placeOrder(Request $request)
    {
        // Detailed logging
        Log::info('=== ORDER PLACEMENT STARTED ===');
        Log::info('Request Method: ' . $request->method());
        Log::info('Request URL: ' . $request->fullUrl());
        Log::info('Request IP: ' . $request->ip());
        Log::info('Session ID: ' . session()->getId());
        Log::info('All Request Data:', $request->all());
        
        // Check authentication first
        if (!Auth::check()) {
            Log::error('User not authenticated');
            return redirect()->route('login')->with('error', 'Please log in to place an order.');
        }
        
        Log::info('User authenticated:', [
            'user_id' => Auth::id(), 
            'name' => Auth::user()->name,
            'email' => Auth::user()->email
        ]);

        // Validate request
        try {
            $validated = $request->validate([
                'cart' => 'required|string',
                'total_amount' => 'required|numeric|min:0',
                'payment_method' => 'required|in:COD,PayHere'
            ]);
            Log::info('Validation passed', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return redirect()->route('order.checkout')
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Invalid order data. Please try again.');
        }

        $cart = json_decode($request->input('cart'), true);
        $totalAmount = $request->input('total_amount');
        $paymentMethod = $request->input('payment_method');
        
        Log::info('Cart decoded:', ['items_count' => is_array($cart) ? count($cart) : 'not an array']);
        
        if (empty($cart) || !is_array($cart)) {
            Log::error('Cart is empty or invalid', [
                'cart_raw' => $request->input('cart'),
                'cart_decoded' => $cart,
                'is_array' => is_array($cart)
            ]);
            return redirect()->route('market')->with('error', 'Your cart is empty or invalid.');
        }

        // Get buyer_id
        $user = Auth::user();
        $buyerId = $user->user_id;

        Log::info('Starting transaction', ['buyer_id' => $buyerId]);

        DB::beginTransaction();
        try {
            // Create main order
            $order = Order::create([
                'buyer_id' => $buyerId,
                'total_price' => $totalAmount,
                'status' => 'Pending',
                'created_at' => now(),
            ]);

            Log::info('Order created successfully', [
                'order_id' => $order->order_id,
                'buyer_id' => $buyerId,
                'total_price' => $totalAmount
            ]);

            foreach ($cart as $index => $item) {
                Log::info("Processing cart item {$index}", ['item' => $item]);
                
                $quantity = $item['quantity'] ?? $item['qty'] ?? 0;
                
                if ($quantity <= 0) {
                    throw new \Exception("Invalid quantity for item: " . ($item['name'] ?? 'Unknown'));
                }

                $crop = Crop::find($item['id']);
                
                if (!$crop) {
                    throw new \Exception("Crop ID {$item['id']} not found.");
                }
                
                Log::info('Crop found', [
                    'crop_id' => $crop->crop_id,
                    'name' => $crop->crop_name,
                    'available' => $crop->quantity_available
                ]);
                
                if ($crop->quantity_available < $quantity) {
                    throw new \Exception("Insufficient stock for {$crop->crop_name}. Available: {$crop->quantity_available} kg, Requested: {$quantity} kg");
                }

                // Create order item
                $orderItem = OrderItem::create([
                    'order_id' => $order->order_id,
                    'crop_id' => $crop->crop_id,
                    'quantity' => $quantity,
                    'unit_price' => $crop->price,
                ]);

                Log::info('Order item created', [
                    'order_item_id' => $orderItem->order_item_id,
                    'crop' => $crop->crop_name,
                    'quantity' => $quantity
                ]);

                // Deduct stock
                $crop->decrement('quantity_available', $quantity);
                Log::info('Stock updated', [
                    'crop' => $crop->crop_name,
                    'new_quantity' => $crop->fresh()->quantity_available
                ]);
            }

            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->order_id,
                'payment_method' => $paymentMethod === 'COD' ? 'Cash' : 'Mobile Payment',
                'payment_status' => 'Pending',
                'amount' => $totalAmount,
                'created_at' => now(),
            ]);

            Log::info('Payment record created', [
                'payment_id' => $payment->payment_id,
                'order_id' => $order->order_id,
                'method' => $paymentMethod,
                'status' => $payment->payment_status
            ]);

            DB::commit();
            Log::info('Transaction committed successfully');

            // Clear cart from session
            session()->forget('cart');
            Log::info('Cart cleared from session');

            if ($paymentMethod === 'COD') {
                Log::info('Redirecting to order success (COD)', ['order_id' => $order->order_id]);
                return redirect()->route('order.success')->with('order_id', $order->order_id);
            }

            // PayHere redirect
            Log::info('Preparing PayHere redirect');
            return $this->redirectToPayHere($order);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== ORDER PLACEMENT FAILED ===', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('order.checkout')
                ->with('error', 'Order failed: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Redirect to PayHere
     */
    private function redirectToPayHere(Order $order)
{
    $merchantId = env('PAYHERE_MERCHANT_ID');         // Sandbox Merchant ID
    $merchantSecret = env('PAYHERE_MERCHANT_SECRET'); // Sandbox Merchant Secret

    // Use full URLs matching your sandbox domain
    $returnUrl = 'http://agri.local:8000/order/success';
    $cancelUrl = 'http://agri.local:8000/order/cancel';
    $notifyUrl = 'http://agri.local:8000/order/notify';


    // Sanitize item names: only letters, numbers, spaces, commas
    $items = $order->orderItems->map(function($item) {
        $name = preg_replace('/[^A-Za-z0-9\s,]/', '', $item->crop->crop_name);
        return $name . ' x ' . $item->quantity . 'kg';
    })->join(', ');

    // Format amount to 2 decimal places
    $amount = number_format($order->total_price, 2, '.', '');

    // Calculate hash (PayHere requires lowercase MD5)
    $hashString = $merchantId . $order->order_id . $amount . 'LKR' . md5($merchantSecret);
    $hashedSecret = strtolower(md5($hashString));

    // Build payload
    $payload = [
        'merchant_id' => $merchantId,
        'return_url' => $returnUrl,
        'cancel_url' => $cancelUrl,
        'notify_url' => $notifyUrl,
        'order_id' => $order->order_id,
        'items' => $items,
        'currency' => 'LKR',
        'amount' => $amount,
        'first_name' => Auth::user()->name,
        'last_name' => '',
        'email' => Auth::user()->email,
        'phone' => Auth::user()->contact_no ?? '',
        'address' => '',
        'city' => '',
        'country' => 'Sri Lanka',
        'hash' => $hashedSecret,
    ];

    // Log for debugging
    Log::info('PayHere Payload (sandbox-ready):', $payload);

    // Return view for auto-redirect
    return view('payhere_redirect', compact('payload'));
}


    /**
     * PayHere Success callback
     */
    public function success(Request $request)
    {
        $orderId = session('order_id') ?? $request->query('order_id') ?? $request->input('order_id');
        
        Log::info('Order success callback', [
            'order_id' => $orderId,
            'request_data' => $request->all()
        ]);

        $order = Order::find($orderId);

        if ($order) {
            // Check if payment was via PayHere
            if ($request->has('payment_id')) {
                $order->update(['status' => 'Approved']);
                $order->payment()->update(['payment_status' => 'Completed']);
            }
        }

        return view('orderconfirmation', compact('order'));
    }

    public function cancel()
    {
        Log::info('Payment cancelled by user');
        return redirect()->route('market')->with('error', 'Payment was cancelled.');
    }

    public function notify(Request $request)
    {
        Log::info('PayHere notify callback', ['data' => $request->all()]);

        $merchantId = env('PAYHERE_MERCHANT_ID');
        $merchantSecret = env('PAYHERE_MERCHANT_SECRET');
        $orderId = $request->input('order_id');
        $paymentId = $request->input('payment_id');
        $payHereAmount = $request->input('payhere_amount');
        $payHereCurrency = $request->input('payhere_currency');
        $statusCode = $request->input('status_code');
        $md5sig = $request->input('md5sig');

        // Verify hash
        $localMd5sig = strtoupper(
            md5(
                $merchantId . 
                $orderId . 
                $payHereAmount . 
                $payHereCurrency . 
                $statusCode . 
                strtoupper(md5($merchantSecret))
            )
        );

        if ($localMd5sig === $md5sig && $statusCode == 2) {
            $order = Order::find($orderId);
            if ($order) {
                $order->update(['status' => 'Approved']);
                $order->payment()->update([
                    'payment_status' => 'Completed',
                    'status' => 'Active'
                ]);
                
                Log::info('Payment completed successfully', ['order_id' => $orderId]);
            }
        } else {
            Log::error('PayHere hash verification failed', [
                'expected' => $localMd5sig,
                'received' => $md5sig
            ]);
        }

        return response()->json(['success' => true]);
    }
}