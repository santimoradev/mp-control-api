<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Sentinel;

use App\Models\User;

class ProductPriceController extends CoreController
{
  public function index(Request $request)
  {
    return $this->result();
  }
  public function store(Request $request)
  {
    DB::beginTransaction();
    try {
      $take = [
        'business_id', 'user_id', 'location_id', 'product_id', 'price', 'year', 'month'
      ];
      DB::commit();
    } catch (\Exception $e) {
      $this->addErrorMessage('Ha ocurrido un error', $e->getMessage() );
      DB::rollBack();
    }
    return $this->result();
  }
}
