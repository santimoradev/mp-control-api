<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Visit;
use App\Services\MediaUploader;
use App\Http\Resources\VisitCollection;

class DashboardController extends CoreController
{
  public function index(Request $request)
  {

    $today = Carbon::now()->format('Y-m-d');


    $totalScheduled = Visit::where('status', '!=', 0)->count();

    $todayVisits = Visit::where('status', '!=', 0)
        ->whereDate('scheduled_date', $today )
        ->count();

    $completedToday = Visit::where('status', 3)
        ->whereDate('scheduled_date', $today )
        ->count();
    $visitsThisMonth = Visit::where('status', '!=', 0)
        ->whereMonth('scheduled_date', Carbon::now()->month)
        ->whereYear('scheduled_date', Carbon::now()->year)
        ->count();

    $startOfWeek = Carbon::now()->startOfWeek();
    $endOfWeek = Carbon::now()->endOfWeek();

    $weekStatus = Visit::selectRaw("
            SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as completed,
            SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as in_progress,
            SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as pending
        ")
        ->whereBetween('scheduled_date', [
            $startOfWeek,
            $endOfWeek
        ])
        ->first();


    $visits = Visit::selectRaw("
          DATE(scheduled_date) as date,
          SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as complete,
          SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as pending,
          SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as inprogress
      ")
      ->whereBetween('scheduled_date', [
          Carbon::yesterday()->subDays(6)->startOfDay(),
          Carbon::yesterday()->endOfDay()
      ])
        ->where('status','!=',0)
      ->groupByRaw('DATE(scheduled_date)')
      ->orderBy('date')
      ->get()
      ->keyBy('date');


    $trend = collect();

    for ($i = 6; $i >= 0; $i--) {

        $date = Carbon::yesterday()->subDays($i)->format('Y-m-d');

        $trend->push([
            'date' => $date,
            'complete' => $visits[$date]->complete ?? 0,
            'pending' => $visits[$date]->pending ?? 0,
            'inprogress' => $visits[$date]->inprogress ?? 0
        ]);
    }

    $completionRate = Visit::where('status', '!=', 0)
        ->whereMonth('scheduled_date', now()->month)
        ->selectRaw("
            COUNT(*) as total,
            SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as completed
        ")
        ->first();

    $rate = round(($completionRate->completed / $completionRate->total) * 100);

    $inProgress = Visit::where('status', 2)->whereDate('scheduled_date', $today )->count();

    $data = [
      'totalScheduled' => $totalScheduled,
      'todayVisits' => $todayVisits,
      'completedToday' => $completedToday,
      'visitsThisMonth' => $visitsThisMonth,
      'trend' => $trend,
      'inProgress' => $inProgress,
      'completionRate' => $completionRate,
      'rate' => $rate,
    ];
    $this->setData( $data );
    return $this->result();
  }
}
