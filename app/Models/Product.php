<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'products';

    protected $fillable = [
        'name', 'brand_id', 'measure_type', 'measure_unit', 'price', 'presentation'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}
