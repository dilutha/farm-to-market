<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payment'; // Specify the correct table name
    protected $primaryKey = 'payment_id';
    
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'payment_status',
        'status',
        'created_at'
    ];

    public $timestamps = false; // Since you're manually setting created_at

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}