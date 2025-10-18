<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = 'order_items';
    protected $primaryKey = 'order_item_id';
    public $timestamps = false;
    
    protected $fillable = [
        'order_id',
        'crop_id',
        'quantity',
        'unit_price'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function crop()
    {
        return $this->belongsTo(Crop::class, 'crop_id', 'crop_id');
    }

    // Accessor for subtotal
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->unit_price;
    }
}