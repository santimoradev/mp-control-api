<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'visits';
    protected $fillable = [
        'business_id', 'user_id', 'location_id', 'started_at', 'started_media_id', 'started_lat', 'started_lng', 'finished_at', 'finished_media_id', 'finished_lat', 'finished_lng', 'observations'
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
    public function startedMedia()
    {
        return $this->belongsTo(Media::class, 'started_media_id');
    }
    public function finishedMedia()
    {
        return $this->belongsTo(Media::class, 'finished_media_id');
    }
}
