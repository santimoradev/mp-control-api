<?php

namespace App\Queries;
use Carbon\Carbon;
use Carbon\CarbonPeriod;


use Illuminate\Support\Facades\DB;
use App\Models\Aditional;
use App\Models\Exhibition;

class PhotosReportQuery
{
    public static function getExhibitions($params)
    {
        $query = Exhibition::query();

        $query->with(['beforeMedia', 'afterMedia', 'location.province', 'location.city']);

        if ( !empty( $params['provinceId'] ) ) :
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

        $query->orderBy('observed_at', 'Desc');
        return $query;
    }
    public static function getAditionals($params)
    {
        $query = Aditional::query();

        $query->with(['media', 'location.province', 'location.city']);

        if ( !empty( $params['provinceId'] ) ) :
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

        $query->orderBy('observed_at', 'Desc');
        return $query;
    }
}
