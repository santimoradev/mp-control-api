<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

use App\Models\Province;
use App\Models\City;
use App\Models\Media;

class Location extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'locations';
    protected $fillable = [
        'name', 'address' , 'province_id', 'city_id', 'media_id', 'zoom', 'lat', 'lng', 'description'
    ];
    protected $hidden = [
        'created_at', 'pivot', 'deleted_at'
    ];

    protected $casts = [
      'lat' => 'float',
      'lng' => 'float',
    ];
    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = !empty($value) ? $value : '';
    }
    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = !empty($value) ? $value : '';
    }
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
