<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;
use Carbon\Carbon;

use App\Services\MediaUploader;
use App\Http\Resources\VisitResource;
use App\Models\User;
use App\Models\Visit;
use App\Models\Exhibition;
use App\Models\Aditional;
use App\Models\ProductObservation;

class VisitTaskController extends CoreController
{
  public function show(Request $request, $id )
  {

    $visit = Visit::find($id);

    $this->addData('exhibitions', $visit->exhibitions->count() );
    $this->addData('aditionals', $visit->aditionals->count());
    $this->addData('competence', $visit->competence->count());
    $this->addData('observations', $visit->observations->count());

    return $this->result();
  }
  public function getExhibitions(Request $request, $id )
  {
    $visit = Visit::find($id);

    $visit->load(['exhibitions.beforeMedia','exhibitions.afterMedia']);
    $this->addData('rows', $visit->exhibitions);
    return $this->result();
  }
  public function createExhibitions(Request $request,  MediaUploader $mediaUploader, $id )
  {
    DB::beginTransaction();
    try {

      $take = ['name','before_description', 'after_description'];
      $input = $request->only($take);
      $this->addData('request', $request->all());

    if ( !$request->has('after_file') OR !$request->has('before_file') ) :
        $this->addErrorMessage(
          'Sin evidencia',
          'Debe adjuntar una foto.'
        );
        return $this->result();
    endif;

      $stamp  = Carbon::now();
      $visit = Visit::find($id);
      if ( $exists = $visit->exhibitions()->exists() ) :
        $this->addErrorMessage(
          'Ya existe una exhibición',
          'Ya existe una exhibición de este tipo para esta visita.'
        );
        return $this->result();
      endif;


      $exhibition = Exhibition::create([
        'visit_id' => $visit->id,
        'name' => $input['name'],
        'business_id' => $visit->route->business_id,
        'location_id' => $visit->location_id,
        'before_description' => $input['before_description'] ?? '',
        'after_description' => $input['after_description'] ?? '',
        'created_by' => $this->user->id,
        'observed_at' => $visit->scheduled_date,
      ]);

      if ( $request->has('before_file') AND $request->file('before_file') ) :
        $picture = $request->file('before_file');
        $folder = 'exhibitions';
        $picture = $mediaUploader->upload( $folder, $picture );
        $exhibition->before_media_id = $picture->id;
        $exhibition->save();
      endif;
      if ( $request->has('after_file') AND $request->file('after_file') ) :
        $picture = $request->file('after_file');
        $folder = 'exhibitions';
        $picture = $mediaUploader->upload( $folder, $picture );
        $exhibition->after_media_id = $picture->id;
        $exhibition->save();
      endif;
      $this->addSuccessMessage( 'Exhibición creada' , 'La exhibición ha sido creada exitosamente.' );
      $this->addData('data', $exhibition );

      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function getAditionals(Request $request, $id )
  {
    $visit = Visit::find($id);

    $visit->load(['aditionals.media']);
    $this->addData('rows', $visit->aditionals);
    return $this->result();
  }
  public function createAditionals(Request $request,  MediaUploader $mediaUploader, $id )
  {
    DB::beginTransaction();
    try {

      $take = ['name','description', 'type'];
      $input = $request->only($take);

      $stamp  = Carbon::now();
      $visit = Visit::find($id);

      $type = $input['type'] ?? 1;

      $exhibition = Aditional::create([
        'name' => $input['name'],
        'visit_id' => $visit->id,
        'business_id' => $visit->route->business_id,
        'location_id' => $visit->location_id,
        'created_by' => $this->user->id,
        'type' => $input['type'] ?? 1,
        'description' => $input['description'] ?? '',
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
  public function getObservations(Request $request, $id )
  {
    $visit = Visit::find($id);

    $visit->load(['observations.product']);
    $this->addData('rows', $visit->observations);
    return $this->result();
  }
  public function createObservations(Request $request, $id )
  {
    DB::beginTransaction();
    try {

      $take = ['rows'];
      $input = $request->only($take);

      $stamp  = Carbon::now();
      $visit = Visit::find($id);
      foreach( $input['rows'] as $row ) :

        ProductObservation::create([
          'visit_id' => $visit->id,
          'business_id' => $visit->route->business_id,
          'location_id' => $visit->location_id,
          'created_by' => $this->user->id,
          'product_id' => $row['id'],
          'price' => $row['price'],
          'stock' => $row['stock'],
          'expiration_date' => $row['expiration_date'],
          'observed_at' => $visit->scheduled_date,
        ]);
      endforeach;
      $this->addSuccessMessage( 'Registro creado' , 'Los registros guardados exitosamente.' );
      $this->setData(true);

      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
}
