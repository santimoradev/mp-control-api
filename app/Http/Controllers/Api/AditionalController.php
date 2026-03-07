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
use App\Models\Exhibition;
use App\Services\MediaUploader;
use App\Http\Resources\VisitResource;

class AditionalController extends CoreController
{
  public function createFromVisit(Request $request,  MediaUploader $mediaUploader, $id )
  {
    DB::beginTransaction();
    try {

      $take = ['description', 'type'];
      $input = $request->only($take);

      $stamp  = Carbon::now();
      $visit = Visit::find($id);

      $type = $input['type'] ?? 1;

      if (in_array($type, [1, 2])) :
        $exists = Exhibition::where('location_id', $visit->location_id)
            ->where('observed_at', $visit->scheduled_date)
            ->where('type', $type)
            ->exists();

        if ($exists) :
          $this->addErrorMessage(
            'Ya existe una exhibición adicional',
            'Ya existe una exhibición adicional de este tipo para esta visita.'
          );
          return $this->result();
        endif;
      endif;


      $exhibition = Exhibition::create([
        'visit_id' => $visit->id,
        'business_id' => $visit->route->business_id,
        'location_id' => $visit->location_id,
        'description' => $input['description'] ?? '',
        'created_by' => 1,
        'type' => $input['type'] ?? 1,
        'observed_at' => $visit->scheduled_date,
      ]);

      if ( $request->has('file') AND $request->file('file') ) :
        $picture = $request->file('file');
        $folder = 'exhibitions';
        $picture = $mediaUploader->upload( $folder, $picture );
        $exhibition->media_id = $picture->id;
        $exhibition->save();
      endif;
      $this->addSuccessMessage( 'Exhibición adicional creada' , 'La exhibición adicional ha sido creada exitosamente.' );

      $this->setData(true);

      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function getFromVisit(Request $request,  MediaUploader $mediaUploader, $id )
  {

    $visit = Visit::find($id);

    $visit->load([
      'exhibitions.media',
      'aditionals.media',
      'competence.media'
    ]);

    $this->addData('exhibitions', $visit->exhibitions);
    $this->addData('aditionals', $visit->aditionals);
    $this->addData('competence', $visit->competence);

    return $this->result();
  }
}
