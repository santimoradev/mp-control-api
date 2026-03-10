<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductObservation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'observations';

    protected $fillable = [
        'visit_id', 'business_id', 'location_id', 'created_by', 'product_id', 'price', 'stock', 'observed_at', 'expiration_date'
    ];

    protected $casts = [
      'price' => 'float',
      'stock' => 'integer',
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
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function scopeReportFilters($query, $params)
    {

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
}
