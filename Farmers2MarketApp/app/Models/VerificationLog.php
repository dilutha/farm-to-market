<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationLog extends Model
{
    use HasFactory;
    
    protected $table = 'Verification_Log';
    protected $primaryKey = 'verification_id';
    public $timestamps = false;
    
    const CREATED_AT = 'action_date';
    const UPDATED_AT = null;
    
    protected $fillable = [
        'admin_id',
        'target_type',
        'target_id',
        'action',
        'remarks'
    ];
    
    public function admin() {
        return $this->belongsTo(Admin::class, 'admin_id');
    }
}