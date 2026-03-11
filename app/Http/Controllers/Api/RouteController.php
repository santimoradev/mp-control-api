<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;
use App\Models\Route;
use App\Models\VisitPlanStop;
use App\Services\MediaUploader;
use App\Http\Resources\VisitResource;

class RouteController extends CoreController
{
  public function index(Request $request)
  {

    $query = Route::query();

    $take = ['search'];
    $input = $request->only($take);

    if ( $request->has('search') AND $request->search) :
      $query->where( function($subquery) use ($input) {
        $subquery->orWhere('title','LIKE','%'.$input['search'].'%');
      });
    endif;
    $query->with([
      'business', 'createdBy'
    ]);
    $query->withCount('visits')->get();
    $query->withCompliance();
    $query->orderBy('id', 'Desc');

    $rows = $query->paginate(10);

    $this->setData( new BaseCollection($rows) );
    return $this->result();
  }
  public function store(Request $request)
  {
    DB::beginTransaction();
    try {
      $take = ['title', 'business_id', 'assigned_to', 'locations','start_date', 'end_date'];
      $input = $request->only($take);

      $route = Route::create([
        'business_id' => $input['business_id'],
        'created_by' => 1,
        'title' => $input['title'],
        'start_date' => $input['start_date'],
        'end_date' => $input['end_date'].' 23:59:59',
        'status' => 1
      ]);
      foreach( $input['locations'] as $location) :
        foreach( $location['dates'] as $locationDate ) :
          $route->visits()->create([
            'location_id' => $location['id'],
            'assigned_to' => $input['assigned_to'],
            'scheduled_date' => $locationDate
          ]);
        endforeach;
      endforeach;
      $this->setData(true);
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function show(Request $request, $id)
  {

    $query = Route::query();

    $query->with([
      'business', 'createdBy'
    ]);

    $queryVisits = clone $query;

    $queryVisits->with([
      'visits.location.province', 'visits.location.city', 'visits.assignedTo'
    ]);

    $query->withCount('visits')->get();

    $row = $query->find($id);

    $rowVisits = $queryVisits->find($id);
    $visits = $rowVisits->visits()->orderBy('scheduled_date', 'desc')->get();
    $this->addData( 'route', $row );
    $this->addData( 'visits', VisitResource::collection($visits) );
    return $this->result();
  }
}
