<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmersProfile extends Model
{
    use HasFactory;
    
    protected $table = 'Farmers_Profile'; // Fixed: was 'farmers_profile'
    protected $primaryKey = 'farmer_id';
    public $incrementing = false;
    public $timestamps = false; // Your table doesn't have timestamps
    
    protected $fillable = [
        'farmer_id',
        'farmer_code',
        'location',
        'farm_size',
        'verification_status',
        'status',
    ];
    
    // Remove boot() - you have a database trigger for this
    
    public function user() {
        return $this->belongsTo(User::class, 'farmer_id', 'user_id');
    }
    
    public function crops() {
        return $this->hasMany(Crop::class, 'farmer_id', 'farmer_id');
    }
}