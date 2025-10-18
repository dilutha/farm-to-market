<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerProfile extends Model
{
    use HasFactory;

    // Table and primary key
    protected $table = 'farmer_profiles'; // table name in lowercase
    protected $primaryKey = 'farmer_id';
    public $incrementing = false; // user_id is used as PK, set externally
    public $timestamps = false; // no created_at/updated_at columns in table

    // Mass assignable fields
    protected $fillable = [
        'farmer_id',
        'farmer_code',
        'location',
        'farm_size',
        'verification_status',
        'status',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'farmer_id', 'user_id');
    }

    public function crops()
    {
        return $this->hasMany(Crop::class, 'farmer_id', 'farmer_id');
    }
}
