<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exhibition extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'exhibitions';
    protected $fillable = [
        'business_id', 'user_id', 'location_id', 'media_id'
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
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
