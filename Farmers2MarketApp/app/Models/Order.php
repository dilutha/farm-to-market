<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'buyer_id', 'crop_id', 'quantity', 'total_price', 'status', 'created_at'
    ];

    public function crop() {
        return $this->belongsTo(Crop::class, 'crop_id', 'crop_id');
    }

    public function buyer() {
        return $this->belongsTo(BuyerProfile::class, 'buyer_id', 'buyer_id');
    }
}
