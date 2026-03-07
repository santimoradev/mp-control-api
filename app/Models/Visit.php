<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Visit extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'visits';
    protected $fillable = [
        'route_id',
        'location_id',
        'assigned_to',
        'scheduled_date',
        'check_in', 'check_out',
        'media_id',
        'lat', 'lng',
        'status'
    ];

    protected $casts = [
      'check_in' => 'datetime',
      'check_out' => 'datetime',
      'lat' => 'float',
      'lng' => 'float',
      'status' => 'integer'
    ];

    public function getMaskIdAttribute()
    {
      return Hashids::encode($this->id);
    }
    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }

    public function exhibitions()
    {
        return $this->hasMany(Exhibition::class, 'visit_id');
    }

    public function aditionals()
    {
        return $this->hasMany(Aditional::class, 'visit_id')
            ->whereIn('type', [1,2]);
    }

    public function competence()
    {
        return $this->hasMany(Aditional::class, 'visit_id')
            ->where('type', 3);
    }
    public function observations()
    {
        return $this->hasMany(ProductObservation::class, 'visit_id');
    }
}
