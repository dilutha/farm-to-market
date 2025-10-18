<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;
    
    protected $fillable = [
        'buyer_id',
        'total_price',
        'status',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id', 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    // Add alias for backward compatibility
    public function items()
    {
        return $this->orderItems();
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }
}