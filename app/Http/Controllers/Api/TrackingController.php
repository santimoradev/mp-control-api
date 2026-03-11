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
use App\Models\VisitPlanStop;
use App\Services\MediaUploader;
use App\Http\Resources\VisitCollection;

class TrackingController extends CoreController
{
  public function index(Request $request)
  {


    $take = ['date'];
    $input = $request->only($take);

    if ( !$request->has('date') ) :
      $input['date'] = Carbon::now()->format('Y-m-d');
    endif;

    $query = Visit::query();

    $query->join('locations', 'locations.id', '=', 'visits.location_id');
    $query->whereDate('scheduled_date', $input['date']);
    $query->where('status', '>' , 0 );

    $query->with([
      'route.business', 'location.province', 'location.city', 'assignedTo'
    ]);

    $query->orderBy('locations.name', 'Asc');
    $rows = $query->paginate(20);

    $this->setData(new VisitCollection($rows));
    return $this->result();
  }
}
