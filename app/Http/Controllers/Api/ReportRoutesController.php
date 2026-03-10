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

use App\Queries\VisitReportQuery;

class ReportRoutesController extends CoreController
{
  public function visits(Request $request)
  {

    $take = ['provinceId', 'cityId', 'dates'];
    $input = $request->only($take);

    $rows = VisitReportQuery::build($input)
        ->paginate(20);

    $this->setData(new BaseCollection($rows));

    return $this->result();
  }

}
