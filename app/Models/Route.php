<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Route extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'routes';
    protected $fillable = [
      'business_id', 'created_by', 'title', 'start_date', 'end_date', 'status'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function visits()
    {
        return $this->hasMany(Visit::class, 'route_id');
    }
}
