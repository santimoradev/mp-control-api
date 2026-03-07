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

class ReportController extends CoreController
{
  public function visits(Request $request)
  {

    $take = ['province_id', 'city_id', 'date_from', 'date_to'];
    $input = $request->only($take);
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
    ->where('status', 3);

    if ( $request->has('province_id') ) :
      $query->whereHas('location', function ($q) use ($input) {
          $q->where('province_id', $input['province_id']);
      });
    endif;

    if ( $request->has('city_id') ) :
      $query->whereHas('location', function ($q) use ($input) {
          $q->where('city_id', $input['city_id']);
      });
    endif;


    if ( $request->has('date_from') ) :
      $query->whereDate('scheduled_date' , '>=' , $input['date_from']);
    endif;

    if ( $request->has('date_to') ) :
      $query->whereDate('scheduled_date' , '<=' , $input['date_to']);
    endif;
    $query->with([
      'media', 'assignedTo', 'location.province', 'location.city'
    ]);

    $query->orderByDesc('scheduled_date');

    $rows = $query->paginate(20) ;

    $this->setData( new BaseCollection( $rows ) );

    return $this->result();
  }
}
