<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Models\Visit;
use App\Models\ProductObservation;
use App\Queries\InventoryReportQuery;
use App\Queries\ProductsQuery;

class ReportProductsController extends CoreController
{
    public function getInventory(Request $request)
    {

      $take = ['provinceId', 'cityId', 'dates'];
      $input = $request->only($take);

      $inventoryData = InventoryReportQuery::build($input)
              ->paginate(20);

      $this->setData( new BaseCollection( $inventoryData ));
      return $this->result();
    }

    public function getInventoryWidgets()
    {

      $lowStockProducts = InventoryReportQuery::lowStockProducts();
      $productsExpiringSoon = InventoryReportQuery::productsExpiringSoon();

      $this->addData('lowStockProducts', $lowStockProducts->get());
      $this->addData('productsExpiringSoon', $productsExpiringSoon->get());
      return $this->result();
    }

    public function getRangePrices(Request $request)
    {

      $take = ['provinceId', 'cityId', 'dates'];
      $input = $request->only($take);

      $data = ProductsQuery::getMonths($input);
      $query = ProductsQuery::rangePrices($data['start'], $data['end'], $input);

      $rows = $query->paginate(20);
      $this->addData('months', $data['months'] );
      $this->addData('prices', new BaseCollection($rows) );
      return $this->result();
    }
    public function getMarketAverage(Request $request)
    {

      $take = ['provinceId', 'cityId', 'dates'];
      $input = $request->only($take);

      $query = ProductsQuery::getMarkeAverage($input);
      $rows = $query->paginate(20);

      $this->setData(new BaseCollection($rows) );

      return $this->result();
    }

}
