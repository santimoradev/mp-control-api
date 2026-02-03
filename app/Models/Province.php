<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\City;

class Province extends Model
{
    use HasFactory;
    protected $table = 'provinces';
    public $timestamps = false;
    protected $fillable = [
        'name'
    ];
    public function cities()
    {
        return $this->hasMany(City::class, 'province_id');
    }
}
