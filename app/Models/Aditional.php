<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aditional extends Model
{
    use HasFactory;
    use SoftDeletes;


    const TYPE_ADITIONAL = 1;
    const TYPE_ADITIONAL_PAYMENT = 2;
    const TYPE_COMPETENCE = 3;

    protected $table = 'aditionals';
    protected $fillable = [
        'name', 'source_id', 'visit_id', 'business_id', 'location_id', 'created_by', 'type', 'media_id', 'description', 'observed_at'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
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
