<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\FarmerProfile;
use App\Models\BuyerProfile;
use App\Models\Admin;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name','email','password','role','contact_no','status'
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'password'=>'hashed'
    ];

    // Relations
    public function farmerProfile() { return $this->hasOne(FarmerProfile::class,'farmer_id','user_id'); }
    public function buyerProfile() { return $this->hasOne(BuyerProfile::class,'buyer_id','user_id'); }
    public function admin() { return $this->hasOne(Admin::class,'admin_id','user_id'); }

    // Role checks
    public function isFarmer(): bool { return $this->role==='Farmer'; }
    public function isBuyer(): bool { return $this->role==='Buyer'; }
    public function isAdmin(): bool { return $this->role==='Admin'; }

    // Create role profile automatically
    public function createProfile(array $data)
    {
        if($this->isFarmer()){
            $this->farmerProfile()->create([
                'farmer_id'=>$this->user_id,
                'farmer_code'=>'F'.str_pad($this->user_id,4,'0',STR_PAD_LEFT),
                'location'=>$data['location'] ?? null,
                'farm_size'=>$data['farm_size'] ?? 0,
            ]);
        }

        if($this->isBuyer()){
            $this->buyerProfile()->create([
                'buyer_id'=>$this->user_id,
                'buyer_code'=>'B'.str_pad($this->user_id,4,'0',STR_PAD_LEFT),
                'company_name'=>$data['company_name'] ?? null,
                'address'=>$data['address'] ?? null,
            ]);
        }

        if($this->isAdmin()){
            $this->admin()->create([
                'admin_id'=>$this->user_id,
                'designation'=>$data['designation'] ?? 'Admin',
            ]);
        }
    }
}
