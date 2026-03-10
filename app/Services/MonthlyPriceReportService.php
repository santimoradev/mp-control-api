<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class MonthlyPriceReportService
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    protected function latestObservationSubquery()
    {
        return DB::table('observations')
            ->join('locations', 'locations.id', '=', 'observations.location_id')
            ->selectRaw("
                product_id,
                YEAR(observed_at) as year,
                MONTH(observed_at) as month,
                MAX(observed_at) as last_date
            ")
            ->whereBetween('observed_at', [$this->startDate, $this->endDate])
            ->groupByRaw("
                product_id,
                YEAR(observed_at),
                MONTH(observed_at)
            ");
    }

    public function getReport()
    {
        return DB::table('observations as o')
            ->joinSub($this->latestObservationSubquery(), 'latest', function ($join) {

                $join->on('o.product_id', '=', 'latest.product_id')
                    ->whereRaw('YEAR(o.observed_at) = latest.year')
                    ->whereRaw('MONTH(o.observed_at) = latest.month')
                    ->on('o.observed_at', '=', 'latest.last_date');

            })
            ->selectRaw("
                o.product_id,
                DATE_FORMAT(o.observed_at,'%Y-%m') as year_month,
                o.price
            ")
            ->orderBy('o.product_id')
            ->orderByRaw("DATE_FORMAT(o.observed_at,'%Y-%m')")
            ->get();
    }
}
