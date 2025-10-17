<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $table = 'Payment';
    protected $primaryKey = 'payment_id';
    
    protected $fillable = [
        'order_id',
        'amount',
        'payment_method',
        'payment_status',
        'status'
    ];
    
    public function order() {
        return $this->belongsTo(Order::class, 'order_id');
    }
}