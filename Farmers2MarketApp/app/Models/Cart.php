<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    protected $table = 'Cart';
    protected $primaryKey = 'cart_id';
    public $timestamps = false; // You use 'added_at' not 'created_at'
    
    protected $fillable = ['buyer_id', 'crop_id', 'quantity', 'status'];
    
    public function buyerProfile() {
        return $this->belongsTo(BuyerProfile::class, 'buyer_id', 'buyer_id');
    }
    
    public function crop() {
        return $this->belongsTo(Crop::class, 'crop_id');
    }
}