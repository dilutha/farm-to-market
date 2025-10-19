<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FarmerProfile extends Model
{
    use HasFactory;

    // Match table name in DB
    protected $table = 'farmers_profile';

    // Primary key column
    protected $primaryKey = 'farmer_id';

    // If not auto-incrementing (because it’s linked to users.user_id)
    public $incrementing = false;

    // Primary key type
    protected $keyType = 'int';

    // Mass assignable fields
    protected $fillable = [
        'farmer_id',
        'farmer_code',
        'location',
        'farm_size',
    ];

    /**
     * Relationship: each farmer profile belongs to one user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'farmer_id', 'user_id');
    }
}
