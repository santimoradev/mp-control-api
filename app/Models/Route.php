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
    public function scopeWithCompliance($query)
    {

      return $query
          ->select('routes.*')
          ->withCount([
              'visits',

              'visits as visits_completed' => function ($q) {
                  $q->where('status', 3);
              },

              'visits as visits_cancelled' => function ($q) {
                  $q->where('status', 0);
              },

              'visits as visits_pending' => function ($q) {
                  $q->where('status', 1);
              },

              'visits as visits_inprogress' => function ($q) {
                  $q->where('status', 2);
              },

              'visits as visits_expired' => function ($q) {
                  $q->where('status', 4);
              },
          ])
          ->selectRaw("
              CASE
                  WHEN (
                      (select count(*) from visits
                      where visits.route_id = routes.id
                      and visits.status != 0
                      and visits.deleted_at is null)
                  ) = 0
                  THEN 0
                  ELSE ROUND(
                      (
                          (select count(*) from visits
                          where visits.route_id = routes.id
                          and visits.status = 3
                          and visits.deleted_at is null)
                          /
                          (select count(*) from visits
                          where visits.route_id = routes.id
                          and visits.status != 0
                          and visits.deleted_at is null)
                      ) * 100, 2
                  )
              END as compliance_percentage
          ");
    }
}
