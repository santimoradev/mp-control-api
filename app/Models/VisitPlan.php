<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitPlan extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'visit_plans';
    protected $fillable = [
      'business_id', 'assigned_to', 'assigned_from', 'title', 'start_date', 'end_date', 'status'
    ];

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function assignedFrom()
    {
        return $this->belongsTo(User::class, 'assigned_from');
    }
    public function stops()
    {
        return $this->hasMany(VisitPlanStop::class, 'visit_plan_id');
    }
}
