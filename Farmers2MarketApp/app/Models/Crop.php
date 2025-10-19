<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\OrderItem;

class Crop extends Model
{
    protected $table = 'crop';
    protected $primaryKey = 'crop_id';
    public $timestamps = true;

    protected $fillable = [
        'farmer_id',
        'crop_name',
        'description',
        'quantity_available',
        'price',
        'image',
        'status'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function farmer()
    {
        return $this->belongsTo(User::class, 'farmer_id', 'user_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'crop_id', 'crop_id');
    }
}
