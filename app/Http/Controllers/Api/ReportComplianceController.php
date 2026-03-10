<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Visit;

class ReportComplianceController extends CoreController
{

    /**
     * KPI general
     */
    public function general()
    {
        $stats = Visit::query()
            ->selectRaw("
                COUNT(*) as total_visits,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as completed_visits,
                SUM(CASE WHEN status = 4 THEN 1 ELSE 0 END) as expired_visits,
                ROUND(
                    (SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->first();

        $this->setData($stats);
        return $this->result();
    }

    /**
     * Cumplimiento por provincia
     */
    public function byProvince()
    {
        $rows = Visit::query()
            ->join('locations', 'locations.id', '=', 'visits.location_id')
            ->join('provinces', 'provinces.id', '=', 'locations.province_id')
            ->selectRaw("
                provinces.name as province,
                COUNT(*) as total_visits,
                SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupBy('provinces.id','provinces.name')
            ->orderByDesc('compliance_percentage')
            ->get();

        $this->setData($rows);
        return $this->result();
    }

    /**
     * Cumplimiento por ciudad
     */
    public function byCity()
    {
        $rows = Visit::query()
            ->join('locations', 'locations.id', '=', 'visits.location_id')
            ->join('cities', 'cities.id', '=', 'locations.city_id')
            ->selectRaw("
                cities.name as city,
                COUNT(*) as total_visits,
                SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupBy('cities.id','cities.name')
            ->orderByDesc('compliance_percentage')
            ->get();

        return response()->json($rows);
    }

    /**
     * Cumplimiento diario
     */
    public function daily()
    {
        $rows = Visit::query()
            ->selectRaw("
                DATE(scheduled_date) as date,
                COUNT(*) as total_visits,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupByRaw("DATE(scheduled_date)")
            ->orderBy("date")
            ->get();

        return response()->json($rows);
    }

    /**
     * Cumplimiento mensual
     */
    public function monthly()
    {
        $rows = Visit::query()
            ->selectRaw("
                DATE_FORMAT(scheduled_date,'%Y-%m') as month,
                COUNT(*) as total_visits,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupByRaw("DATE_FORMAT(scheduled_date,'%Y-%m')")
            ->orderBy("month")
            ->get();

        return response()->json($rows);
    }

    /**
     * Cumplimiento por usuario
     */
    public function byUser()
    {
        $rows = Visit::query()
            ->join('users', 'users.id', '=', 'visits.assigned_to')
            ->selectRaw("
                users.id,
                CONCAT(users.first_name,' ',users.last_name) as user,
                COUNT(*) as total_visits,
                SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupBy('users.id','users.first_name','users.last_name')
            ->orderByDesc('compliance_percentage')
            ->get();

        return response()->json($rows);
    }

    /**
     * Cumplimiento por ruta
     */
    public function byRoute()
    {
        $rows = Visit::query()
            ->join('routes', 'routes.id', '=', 'visits.route_id')
            ->selectRaw("
                routes.title as route,
                COUNT(*) as total_visits,
                SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupBy('routes.id','routes.title')
            ->orderByDesc('compliance_percentage')
            ->get();

        return response()->json($rows);
    }

    /**
     * Ranking de usuarios por cumplimiento
     */
    public function rankingUsers()
    {
        $rows = Visit::query()
            ->join('users', 'users.id', '=', 'visits.assigned_to')
            ->selectRaw("
                CONCAT(users.first_name,' ',users.last_name) as user,
                COUNT(*) as total_visits,
                SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN visits.status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupBy('users.id','users.first_name','users.last_name')
            ->orderByDesc('completed_visits')
            ->limit(10)
            ->get();

        return response()->json($rows);
    }

    /**
     * Cumplimiento por día de la semana
     */
    public function weekly()
    {
        $rows = Visit::query()
            ->selectRaw("
                DAYNAME(scheduled_date) as day,
                COUNT(*) as total_visits,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as completed_visits,
                ROUND(
                    (SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) / COUNT(*)) * 100,
                    2
                ) as compliance_percentage
            ")
            ->groupByRaw("DAYOFWEEK(scheduled_date)")
            ->orderByRaw("DAYOFWEEK(scheduled_date)")
            ->get();

        return response()->json($rows);
    }

}
