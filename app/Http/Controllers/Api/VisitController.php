<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;
use App\Models\Visit;
use App\Services\MediaUploader;

class VisitController extends CoreController
{
  public function index(Request $request)
  {
    return $this->result();
  }
  public function store(Request $request)
  {
    DB::beginTransaction();
    try {

      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function start(Request $request, MediaUploader $media, $id)
  {
    DB::beginTransaction();
    try {

      $input = [];
      $input['location_id'] = 1;
      $input['business_id'] = 1;
      $input['started_lat'] = $request->lat;
      $input['started_lng'] = $request->lng;
      $input['user_id'] = $this->user->id;
      $visit = Visit::create($input);

      if ( $request->has('file') AND $request->file('file') ) :
          $photo = $request->file('file');
          $folder = 'visits';
          $photo = $media->upload( $folder, $photo );
          $visit->started_media_id = $photo->id;
          $visit->save();
      endif;
      $this->setData(true);
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
}
