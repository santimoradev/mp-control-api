<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;
use App\Models\City;

class CityController extends CoreController
{
  public function index(Request $request, $id)
  {
    $take = [
      's'
    ];
    $input = $request->only($take);
    $query = City::query();
    $query->where('province_id', $id);
    if ( $request->has('s') AND $request->s) :
      $query->where( function($subquery) use ($input) {
        $subquery->orWhere('name','LIKE','%'.$input['s'].'%');
      });
    endif;
    $query->orderBy('name','Asc');
    $rows = $query->get();
    $this->setData($rows );

    return $this->result();
  }
}
