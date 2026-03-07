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
use App\Http\Resources\VisitResource;

class VisitController extends CoreController
{
  public function index(Request $request)
  {
    $take = ['date'];
    $input = $request->only($take);

    $today  = Carbon::now()->format('Y-m-d');
    $query = Visit::query();
    $query->with([
      'location.province', 'assignedTo','location.city', 'route.business'
    ]);
    $query->where('assigned_to', $this->user->id);
    // ADD status different to 1 or 2

    $olderQuery = clone $query;

    $query->where('status', '>' , 0 );
    $query->orderBy('status', 'Asc');

    $olderQuery->whereDate('scheduled_date', '<', $today);
    $olderQuery->orderBy('scheduled_date', 'Desc');
    $olderQuery->orderBy('status', 'Asc');

    $query->whereDate('scheduled_date', '>=', $today);
    $query->orderBy('scheduled_date', 'Asc');

    $this->addData('upcoming', VisitResource::collection($query->get()) );
    $this->addData('overdue', VisitResource::collection($olderQuery->take(8)->get()) );
    $this->addData('now', $today);

    return $this->result();
  }
  public function show( Request $request, $id )
  {

    $today  = Carbon::now()->format('Y-m-d');

    $query = Visit::query();

    $query->with([
      'location.province',
      'location.city',
      'route.business',
      'assignedTo.roles',
      'media',
      'exhibitions',
      'aditionals',
      'competence'
    ]);
    $query->find($id);
    $row = $query->get()->first();
    $scheduled = Carbon::parse($row->scheduled_date)->format('Y-m-d');
    $isToday = $today === $scheduled;
    $this->addData('today', $today);
    $this->addData('scheduled', $scheduled);
    $this->addData('isToday', $isToday);
    $this->addData('row', VisitResource::make($row) );

    return $this->result();
  }
  public function start(Request $request,  MediaUploader $mediaUploader, $id )
  {
    DB::beginTransaction();
    try {

      $take = ['lat','lng'];
      $input = $request->only($take);

      $stamp  = Carbon::now();
      $visit = Visit::find($id);
      if ($visit->status !== 1) :
        $this->addErrorMessage(
          'Visita no válida',
          'La visita no está en estado pendiente.'
        );
        return $this->result();
      endif;

      $visit->update([
        'status' => 2,
        'lat' => $input['lat'],
        'lng' => $input['lng'],
        'check_in' => $stamp
      ]);

      if ( $request->has('file') AND $request->file('file') ) :
          $picture = $request->file('file');
          $folder = 'visits';
          $picture = $mediaUploader->upload( $folder, $picture );
          $visit->media_id = $picture->id;
          $visit->save();
      endif;
      $this->addSuccessMessage( 'Visita iniciada' , 'La visita ha sido iniciada exitosamente.' );

      $this->setData(true);

      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function finish(Request $request, $id )
  {
    DB::beginTransaction();
    try {
      $stamp  = Carbon::now();
      $visit = Visit::find($id);

      $visit->update([
        'status' => 3,
        'check_out' => $stamp
      ]);

      $this->addSuccessMessage( 'Visita finalizada' , 'La visita ha sido finalizada exitosamente.' );
      $this->addData('visit', $visit);
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
}
