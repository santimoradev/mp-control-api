<?php

namespace App\Queries;


use Illuminate\Support\Facades\DB;
use App\Models\Visit;

class VisitReportQuery
{
    public static function build($params)
    {
      $query = Visit::query();
      $query->select(
          'id',
          'route_id',
          'location_id',
          'assigned_to',
          'scheduled_date',
          'check_in',
          'check_out',
          'media_id',
          DB::raw('SEC_TO_TIME(TIMESTAMPDIFF(SECOND, check_in, check_out)) as duration'),
          DB::raw('TIMESTAMPDIFF(SECOND, check_in, check_out) as duration_seconds')
      )
      ->where('status', 3)
      ->with([
          'media',
          'assignedTo',
          'location.province',
          'location.city'
      ])
      ->orderByDesc('scheduled_date');

      if (!empty($params['provinceId'])) :
          $query->whereHas('location', function ($q) use ($params) {
              $q->where('province_id', $params['provinceId']);
          });
      endif;

      if (!empty($params['cityId'])) :
          $query->whereHas('location', function ($q) use ($params) {
              $q->where('city_id', $params['cityId']);
          });
      endif;

      if (!empty($params['dates'])) :
          $query->whereDate('scheduled_date', '>=', $params['dates'][0]);

          if (count($params['dates']) > 1) :
              $query->whereDate('scheduled_date', '<=', $params['dates'][1]);
          endif;
      endif;

      return $query;
    }
}
