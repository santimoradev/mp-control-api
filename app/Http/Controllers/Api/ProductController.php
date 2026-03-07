<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;
use App\Models\Brand;
use App\Models\Product;

class ProductController extends CoreController
{
  public function index(Request $request)
  {
    $take = [
      'search', 'notIn'
    ];
    $input = $request->only($take);
    $query = Product::query();

    if ( $request->has('search') AND $request->search) :
      $query->where( function($subquery) use ($input) {
        $subquery->orWhere('name','LIKE','%'.$input['search'].'%');
      });
    endif;

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
}
