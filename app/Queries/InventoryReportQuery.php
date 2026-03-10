<?php

namespace App\Queries;


use Illuminate\Support\Facades\DB;
use App\Models\ProductObservation;

class InventoryReportQuery
{
    public static function build($params)
    {
      $query = ProductObservation::query();

      $sub = DB::table('observations')
          ->select(
              'product_id',
              'location_id',
              DB::raw('MAX(observed_at) as last_date')
          )
          ->groupBy('product_id', 'location_id');

      $query = ProductObservation::query()
          ->joinSub($sub, 'latest', function ($join) {
              $join->on('observations.product_id', '=', 'latest.product_id')
                  ->on('observations.location_id', '=', 'latest.location_id')
                  ->on('observations.observed_at', '=', 'latest.last_date');
          });

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
          $query->whereDate('observed_at', '>=', $params['dates'][0]);

          if (count($params['dates']) > 1) :
              $query->whereDate('observed_at', '<=', $params['dates'][1]);
          endif;
      endif;

      $query->with([
          'product', 'location.province', 'location.city'
        ])
        ->orderBy('observed_at','Desc')
        ->select(
          'observations.*',
          DB::raw('DATEDIFF(expiration_date, CURDATE()) as days_to_expire')
        );

      return $query;
    }
    public static function lowStockProducts()
    {
      $sub = DB::table('observations')
          ->select(
              'product_id',
              'location_id',
              DB::raw('MAX(observed_at) as last_date')
          )
          ->groupBy('product_id', 'location_id');

      return ProductObservation::query()
          ->joinSub($sub, 'latest', function ($join) {
              $join->on('observations.product_id', '=', 'latest.product_id')
                  ->on('observations.location_id', '=', 'latest.location_id')
                  ->on('observations.observed_at', '=', 'latest.last_date');
          })
          ->join('products', 'products.id', '=', 'observations.product_id')
          ->where('stock', '<', 20)
          ->orderBy('stock')
          ->limit(10)
          ->select([
              'observations.id as id',
              'products.name as product_name',
              'observations.stock',
              'observations.expiration_date',
              DB::raw('DATEDIFF(observations.expiration_date, CURDATE()) as days_to_expire')
          ]);
    }
    public static function productsExpiringSoon()
    {

      $sub = DB::table('observations')
          ->select(
              'product_id',
              'location_id',
              DB::raw('MAX(observed_at) as last_date')
          )
          ->groupBy('product_id', 'location_id');

      return ProductObservation::query()
          ->joinSub($sub, 'latest', function ($join) {
              $join->on('observations.product_id', '=', 'latest.product_id')
                  ->on('observations.location_id', '=', 'latest.location_id')
                  ->on('observations.observed_at', '=', 'latest.last_date');
          })
          ->join('products', 'products.id', '=', 'observations.product_id')
          ->whereNotNull('observations.expiration_date')
          ->whereBetween('observations.expiration_date', [
              now(),
              now()->addDays(15)
          ])
          ->orderBy('observations.expiration_date')
          ->select([
              'observations.id as id',
              'products.name as product_name',
              'observations.stock',
              'observations.expiration_date',
              DB::raw('DATEDIFF(observations.expiration_date, CURDATE()) as days_to_expire')
          ]);
    }
}
