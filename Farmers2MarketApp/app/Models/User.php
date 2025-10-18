<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\FarmerProfile;
use App\Models\BuyerProfile;
use App\Models\Admin;
use App\Models\Inquiry;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Table and primary key
    protected $table = 'users';
    protected $primaryKey = 'user_id';

    // Mass assignable fields
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'contact_no',
        'status',
    ];

    // Hidden fields
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Casts
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed', // Laravel 12+ will hash automatically
    ];

    /**
     * Relationships
     */
    public function farmerProfile()
    {
        return $this->hasOne(FarmerProfile::class, 'farmer_id');
    }

    public function buyerProfile()
    {
        return $this->hasOne(BuyerProfile::class, 'buyer_id');
    }

    public function admin()
    {
        return $this->hasOne(Admin::class, 'admin_id');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class, 'user_id');
    }

    /**
     * Helper methods to check role
     */
    public function isFarmer(): bool
    {
        return $this->role === 'Farmer';
    }

    public function isBuyer(): bool
    {
        return $this->role === 'Buyer';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'Admin';
    }

    /**
     * Create the related profile automatically after registration
     */
    public function createProfile(array $data = [])
    {
        if ($this->isFarmer()) {
            return $this->farmerProfile()->create([
                'farmer_code' => 'F' . str_pad($this->user_id, 4, '0', STR_PAD_LEFT),
                'location' => $data['location'] ?? null,
                'farm_size' => $data['farm_size'] ?? 0,
            ]);
        }

        if ($this->isBuyer()) {
            return $this->buyerProfile()->create([
                'buyer_code' => 'B' . str_pad($this->user_id, 4, '0', STR_PAD_LEFT),
                'company_name' => $data['company_name'] ?? null,
                'address' => $data['address'] ?? null,
            ]);
        }

        if ($this->isAdmin()) {
            return $this->admin()->create([
                'designation' => $data['designation'] ?? 'Admin',
            ]);
        }

        return null;
    }
}
