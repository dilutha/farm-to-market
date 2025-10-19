<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\FarmerProfile;
use App\Models\BuyerProfile;
use App\Models\Crop;
use App\Models\Order;
use App\Models\Payment;

class Admin extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primaryKey = 'admin_id';
    public $timestamps = false; // optional, since your table doesn't have updated_at

    // Relations
    public function user() {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    public function verifyUser($user_id) {
        $user = User::find($user_id);
        if ($user) {
            $user->status = 'Verified';
            $user->save();

            // Log verification
            \DB::table('verification_log')->insert([
                'admin_id' => $this->admin_id,
                'target_type' => 'User',
                'target_id' => $user_id,
                'action' => 'Verified',
                'remarks' => 'User verified successfully',
                'action_date' => now()
            ]);
        }
    }

    public function verifyCrop($crop_id) {
        $crop = Crop::find($crop_id);
        if ($crop) {
            $crop->status = 'Approved';
            $crop->save();

            \DB::table('verification_log')->insert([
                'admin_id' => $this->admin_id,
                'target_type' => 'Crop',
                'target_id' => $crop_id,
                'action' => 'Verified',
                'remarks' => 'Crop approved successfully',
                'action_date' => now()
            ]);
        }
    }
}
