<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyerProfile extends Model
{
    use HasFactory;

    protected $table = 'buyer_profile';
    protected $primaryKey = 'buyer_id';
    public $timestamps = false; // Because your table has no created_at/updated_at

    protected $fillable = [
        'buyer_code', 'company_name', 'address', 'verification_status', 'status'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }

    public function orders() {
        return $this->hasMany(Order::class, 'buyer_id', 'buyer_id');
    }
}
