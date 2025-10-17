<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPrediction extends Model
{
    use HasFactory;
    
    protected $table = 'Market_Prediction';
    protected $primaryKey = 'prediction_id';
    public $timestamps = false;
    
    const CREATED_AT = 'prediction_date';
    const UPDATED_AT = null;
    
    protected $fillable = [
        'crop_id',
        'predicted_price',
        'predicted_demand',
        'model_version',
        'status'
    ];
    
    public function crop() {
        return $this->belongsTo(Crop::class, 'crop_id');
    }
}