<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\FarmerProfile;
use App\Models\BuyerProfile;
use App\Models\Crop;
use App\Models\Order;
use App\Models\Payment;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Check if logged-in user is admin
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Analytics data
        $analytics = [
            'total_users' => User::count(),
            'total_farmers' => FarmerProfile::count(),
            'total_buyers' => BuyerProfile::count(),
            'total_crops' => Crop::count(),
            'total_orders' => Order::count(),
            'total_payments' => Payment::count(),
        ];

        // Get pending farmers
        $pendingUserIds = User::where('status', 'Pending')->where('role', 'Farmer')->pluck('user_id');
        $pendingFarmers = collect();
        
        foreach ($pendingUserIds as $userId) {
            $farmerProfile = FarmerProfile::find($userId);
            if ($farmerProfile) {
                $farmerProfile->user = User::find($userId);
                $pendingFarmers->push($farmerProfile);
            }
        }

        // Get pending buyers
        $pendingBuyerIds = User::where('status', 'Pending')->where('role', 'Buyer')->pluck('user_id');
        $pendingBuyers = collect();
        
        foreach ($pendingBuyerIds as $userId) {
            $buyerProfile = BuyerProfile::find($userId);
            if ($buyerProfile) {
                $buyerProfile->user = User::find($userId);
                $pendingBuyers->push($buyerProfile);
            }
        }

        // Pending Crops - farmer is already a User
        $pendingCrops = Crop::where('status', 'Pending')->with('farmer')->get();

        return view('admin.dashboard', compact('analytics', 'pendingFarmers', 'pendingBuyers', 'pendingCrops'));
    }

    public function verifyUser($id)
    {
        // Check if logged-in user is admin
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        // Find the user by ID
        $userToVerify = User::find($id);

        if (!$userToVerify) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'User not found.');
        }

        // Update user status to Verified
        $userToVerify->status = 'Verified';
        $userToVerify->save();

        // Also verify the related profile (Farmer or Buyer)
        if ($userToVerify->role === 'Farmer') {
            $farmer = FarmerProfile::where('farmer_id', $userToVerify->user_id)->first();
            if ($farmer) {
                try {
                    $farmer->verification_status = 'Verified';
                    $farmer->save();
                } catch (\Exception $e) {
                    // Column doesn't exist, skip
                }
            }
        } elseif ($userToVerify->role === 'Buyer') {
            $buyer = BuyerProfile::where('buyer_id', $userToVerify->user_id)->first();
            if ($buyer) {
                try {
                    $buyer->verification_status = 'Verified';
                    $buyer->save();
                } catch (\Exception $e) {
                    // Column doesn't exist, skip
                }
            }
        }

        return redirect()->route('admin.dashboard')
            ->with('success', $userToVerify->name . ' has been verified successfully!');
    }

    public function verifyCrop($cropId)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $crop = Crop::find($cropId);

        if (!$crop) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Crop not found.');
        }

        $crop->status = 'Approved';
        $crop->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Crop "' . $crop->crop_name . '" has been verified successfully!');
    }

    public function rejectUser($id)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $userToReject = User::find($id);

        if (!$userToReject) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'User not found.');
        }

        $userToReject->status = 'Rejected';
        $userToReject->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'User has been rejected.');
    }

    public function rejectCrop($cropId)
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $crop = Crop::find($cropId);

        if (!$crop) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Crop not found.');
        }

        $crop->status = 'Rejected';
        $crop->save();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Crop has been rejected.');
    }

    public function allUsers()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $users = User::with(['farmerProfile', 'buyerProfile'])->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function allCrops()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $crops = Crop::with('farmer')->paginate(20);
        return view('admin.crops', compact('crops'));
    }

    public function allOrders()
    {
        $user = Auth::user();
        
        if (!$user || $user->role !== 'Admin') {
            return redirect()->route('login')->with('error', 'Unauthorized access.');
        }

        $orders = Order::with(['crop', 'buyerProfile'])->paginate(20);
        return view('admin.orders', compact('orders'));
    }
}