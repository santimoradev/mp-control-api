<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Maatwebsite\Excel\Facades\Excel;
use Sentinel;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Visit;
use App\Exports\VisitsExport;
use App\Exports\InventoryExport;
use App\Exports\RangePricesExport;
use App\Exports\MarketAverageExport;



use App\Queries\ProductsQuery;

class ExportController extends CoreController
{
  public function visits(Request $request)
  {

    $take = ['dates', 'provinceId', 'cityId'];
    $input = $request->only($take);

    $filename = 'visits-';
    $filename .= date('Y_m_d_H_i_s');
    return Excel::download(new VisitsExport($input), $filename.'.xlsx');

    return $this->result();
  }
  public function inventory(Request $request)
  {

    $take = ['dates', 'provinceId', 'cityId'];
    $input = $request->only($take);

    $filename = 'inventory-';
    $filename .= date('Y_m_d_H_i_s');
    return Excel::download(new InventoryExport($input), $filename.'.xlsx');

    return $this->result();
  }

  public function rangePrices(Request $request)
  {
    $take = ['dates', 'provinceId', 'cityId'];
    $input = $request->only($take);
    $data = ProductsQuery::getMonths($input);

    $payload = array_merge(
      $input,
      $data,
    );

    $filename = 'range-prices-';
    $filename .= date('Y_m_d_H_i_s');
    return Excel::download(new RangePricesExport($payload), $filename.'.xlsx');

    return $this->result();
  }
  public function marketAverage(Request $request)
  {
    $take = ['dates', 'provinceId', 'cityId'];
    $input = $request->only($take);

    $filename = 'market-average-';
    $filename .= date('Y_m_d_H_i_s');
    return Excel::download(new MarketAverageExport($input), $filename.'.xlsx');

    return $this->result();
  }
}
