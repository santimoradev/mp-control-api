<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPrice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_prices';

    protected $fillable = [
        'business_id', 'user_id', 'location_id', 'product_id', 'price', 'year', 'month'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
