<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;
use App\Models\Province;

class ProvinceController extends CoreController
{
  public function index(Request $request)
  {
    $take = [
      's'
    ];
    $input = $request->only($take);
    $query = Province::query();

    if ( $request->has('s') AND $request->s) :
      $query->where( function($subquery) use ($input) {
        $subquery->orWhere('name','LIKE','%'.$input['s'].'%');
      });
    endif;
    $query->orderBy('name','Asc');
    $rows = $query->get();
    $this->setData( $rows );

    return $this->result();
  }
}
