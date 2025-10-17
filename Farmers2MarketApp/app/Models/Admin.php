<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    // Table and primary key
    protected $table = 'admins'; // table name in lowercase, plural
    protected $primaryKey = 'admin_id';
    public $incrementing = false; // matches user_id
    public $timestamps = false; // table does not have timestamps

    // Mass assignable fields
    protected $fillable = [
        'admin_id',
        'designation',
        'status',
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }

    public function verificationLogs()
    {
        return $this->hasMany(VerificationLog::class, 'admin_id', 'admin_id');
    }
}
