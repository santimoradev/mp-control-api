<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;
use App\Models\Location;

class LocationController extends CoreController
{
  public function index(Request $request)
  {
    $take = [
      'search', 'provinceId', 'cityId', 'notIn'
    ];
    $input = $request->only($take);
    $query = Location::query();

    if ( $request->has('search') AND $request->search) :
      $query->where( function($subquery) use ($input) {
        $subquery->orWhere('name','LIKE','%'.$input['search'].'%');
      });
    endif;

    if ( $request->has('provinceId') AND $request->provinceId) :
      $query->where('province_id' , $input['provinceId']);
    endif;
    if ( $request->has('cityId') AND $request->cityId) :
      $query->where('city_id' , $input['cityId']);
    endif;

    $query->with(['province', 'city']);
    if ( $request->get('notIn') ) :
      $query->whereNotIn('id', $request->notIn );
    endif;
    if ( $request->get('all')):
      $query->orderBy('name','Asc');
    else:
      $query->orderBy('id','Desc');
    endif;
    $rows = $query->paginate(10);
    $this->setData(new BaseCollection($rows) );

    return $this->result();
  }
  public function store(Request $request)
  {
    DB::beginTransaction();
    try {
      $input = $request->all();
      $location = Location::create( $input );

      $this->setData($location->id);
      $this->addSuccessMessage('Local creado', 'Se ha creado un nuevo local');
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function update(Request $request, $id)
  {
    DB::beginTransaction();
    try {
      $input = $request->all();
      $location = Location::findOrFail( $id );
      $location->update( $input );
      $this->setData(true);
      $this->addSuccessMessage('Local actualizado', 'Se ha actualizado el local');
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
  public function destroy($id)
  {
    DB::beginTransaction();
    try {
      $location = Location::findOrFail( $id );
      $location->delete();
      $this->setData(true);
      $this->addSuccessMessage('Local eliminado', 'Se ha eliminado el local');
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
}
