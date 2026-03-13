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
use App\Services\MediaUploader;

class SyncController extends CoreController
{
  public function visitExpired(Request $request)
  {

    $today = Carbon::now()->format('Y-m-d');
    $yesterday = Carbon::yesterday();

    Visit::where('status', 1)
      ->whereDate('scheduled_date' , '<', now()->startOfDay() )
      ->update([
          'status' => 4
      ]);

    $this->setData( true );

    return $this->result();
  }
}
