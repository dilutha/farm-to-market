<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    protected $table = 'crop';
    protected $primaryKey = 'crop_id';
    public $timestamps = false;
    
    protected $fillable = [
        'farmer_id',
        'crop_name',
        'description',
        'quantity_available',
        'price',
        'image',
        'status',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id', 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'crop_id', 'crop_id');
    }
}