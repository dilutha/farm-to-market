<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Specify the custom table name
    protected $table = 'users';
    
    // Specify the custom primary key
    protected $primaryKey = 'user_id';
    
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_no',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function farmerProfile() {
        return $this->hasOne(FarmerProfile::class, 'farmer_id');
    }

    public function buyerProfile() {
        return $this->hasOne(BuyerProfile::class, 'buyer_id');
    }

    public function admin() {
        return $this->hasOne(Admin::class, 'admin_id');
    }

    public function inquiries() {
        return $this->hasMany(Inquiry::class, 'user_id');
    }
}