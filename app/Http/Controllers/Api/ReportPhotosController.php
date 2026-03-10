<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\CoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\BaseCollection;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Models\Visit;
use App\Models\Exhibition;
use App\Queries\PhotosReportQuery;



class ReportPhotosController extends CoreController
{
    public function getExhibitions(Request $request)
    {

      $take = ['provinceId', 'cityId', 'dates'];
      $input = $request->only($take);

      $query = PhotosReportQuery::getExhibitions($input);

      $data = $query->paginate(20);

      $this->setData( new BaseCollection( $data ));
      return $this->result();
    }
    public function getAditionals(Request $request)
    {

      $take = ['provinceId', 'cityId', 'dates'];
      $input = $request->only($take);

      $query = PhotosReportQuery::getAditionals($input);

      $data = $query->paginate(20);

      $this->setData( new BaseCollection( $data ));
      return $this->result();
    }
}
