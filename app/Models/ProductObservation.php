<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductObservation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'observations';

    protected $fillable = [
        'visit_id', 'business_id', 'location_id', 'created_by', 'product_id', 'price', 'stock', 'observed_at', 'expiration_date'
    ];

    protected $casts = [
      'price' => 'float',
      'stock' => 'integer',
    ];
    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
