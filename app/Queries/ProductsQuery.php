<?php

namespace App\Queries;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


use Illuminate\Support\Facades\DB;
use App\Models\ProductObservation;

class ProductsQuery
{
    public static function rangePrices($start, $end, $params)
    {

      $months = collect(CarbonPeriod::create($start, '1 month', $end))
          ->map(fn ($date) => $date->format('Y-m'))
          ->values();

      $selectMonths = [];

      foreach ($months as $month) :
          $selectMonths[] = DB::raw("
              MAX(CASE
                  WHEN DATE_FORMAT(o.observed_at,'%Y-%m') = '$month'
                  THEN o.price
              END) as `$month`
          ");
      endforeach;
      $columns = ['p.id','p.name'];

      $columns = array_merge(
          $columns,
          $selectMonths,
      );

      $query = DB::table('products as p')
            ->leftJoin('observations as o', function($join) use ($start) {
                $join->on('o.product_id','=','p.id')
                    ->where('o.observed_at','>=',$start);
            })
          ->leftJoin('locations as l','l.id','=','o.location_id')
          ->select($columns)
          ->groupBy('p.id','p.name')
          ->orderBy('p.name');

      if (isset( $params['provinceId'] )) :
        $query->where('l.province_id', $params['provinceId'] );
      endif;

      if (isset( $params['cityId'] )) :
        $query->where('l.city_id', $params['cityId']);
      endif;
      return $query;
    }

    public static function getMonths($params)
    {

      if (isset($params['dates']) AND count( $params['dates'] ) > 1) :

          $startMonth = Carbon::parse($params['dates'][0])->format('Y-m');
          $endMonth = Carbon::parse($params['dates'][1])->format('Y-m');

          $params['dates'] = [$startMonth, $endMonth];

          $start = Carbon::createFromFormat('Y-m', $params['dates'][0])->startOfMonth();
          $end   = Carbon::createFromFormat('Y-m', $params['dates'][1])->endOfMonth();
      else :
          $start = Carbon::now()->subMonths(5)->startOfMonth();
          $end   = Carbon::now()->endOfMonth();
      endif;

      $months = collect(CarbonPeriod::create($start, '1 month', $end))
          ->map(fn ($date) => $date->format('Y-m'))
          ->values();

      return [
        'start' => $start,
        'end' => $end,
        'months' => $months
      ];

    }

    public static function getMarkeAverage($params)
    {
      $query = DB::table('observations as o')
          ->join('products as p', 'p.id', '=', 'o.product_id')
          ->join('locations as l', 'l.id', '=', 'o.location_id')
          ->join('cities as c', 'c.id', '=', 'l.city_id')
          ->join('provinces as pr', 'pr.id', '=', 'c.province_id')
          ->select([
              DB::raw("CONCAT(l.id, '-', p.id) as id"),
              'pr.id as province_id',
              'pr.name as province_name',
              'c.id as city_id',
              'c.name as city_name',
              'l.id as location_id',
              'l.name as location_name',
              'p.id as product_id',
              'p.name as product_name',

              DB::raw('MIN(o.price) as min_price'),
              DB::raw('MAX(o.price) as max_price'),
              DB::raw('AVG(o.price) as avg_price'),
              DB::raw('MAX(o.observed_at) as last_observed_at'),
          ])
          ->whereNotNull('o.price')
          ->groupBy([
              'pr.id',
              'pr.name',
              'c.id',
              'c.name',
              'l.id',
              'l.name',
              'p.id',
              'p.name'
          ])
          ->orderBy('l.name')
          ->orderBy('p.name');


      if (!empty($params['dates'])) :
          $query->whereDate('o.observed_at', '>=', $params['dates'][0]);

          if (count($params['dates']) > 1) :
              $query->whereDate('o.observed_at', '<=', $params['dates'][1]);
          endif;
      endif;
      if (!empty( $params['provinceId'] )) :
        $query->where('l.province_id', $params['provinceId'] );
      endif;

      if (!empty( $params['cityId'] )) :
        $query->where('l.city_id', $params['cityId']);
      endif;

        return $query;
    }
}
