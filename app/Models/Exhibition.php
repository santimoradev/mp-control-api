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
        'name', 'source_id', 'visit_id', 'business_id', 'location_id', 'created_by', 'before_media_id', 'after_media_id', 'before_description', 'after_description', 'observed_at'
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
    public function beforeMedia()
    {
        return $this->belongsTo(Media::class, 'before_media_id');
    }
    public function afterMedia()
    {
        return $this->belongsTo(Media::class, 'after_media_id');
    }
}
