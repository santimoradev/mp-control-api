<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitPlanStop extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'visit_plan_stops';
    protected $fillable = [
      'visit_plan_id', 'location_id', 'scheduled_date', 'status'
    ];

    public function visitPlan()
    {
        return $this->belongsTo(VisitPlan::class, 'visit_plan_id');
    }
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
    public function visit()
    {
        return $this->hasOne(Visit::class, 'visit_plan_stop_id');
    }
}
