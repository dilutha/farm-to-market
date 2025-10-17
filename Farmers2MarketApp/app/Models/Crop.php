<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Crop extends Model
{
    use HasFactory;

    protected $table = 'crop';
    protected $primaryKey = 'crop_id';

    protected $fillable = [
        'farmer_id',
        'crop_name',
        'description',
        'quantity_available',
        'price',
        'image',
        'status',
    ];

    // Relationship to Farmer (optional)
    public function farmer()
    {
        return $this->belongsTo(FarmerProfile::class, 'farmer_id', 'farmer_id');
    }
}
