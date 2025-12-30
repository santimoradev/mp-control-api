<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Vinkla\Hashids\Facades\Hashids;

class Campaign extends Model
{
  use HasFactory;
  use SoftDeletes;
  protected $table = 'campaigns';
  protected $fillable = [
    'mall_id', 'name', 'coupon_value', 'status', 'started_at', 'finished_at'
  ];
  protected $casts = [
    'mall_id' => 'integer',
    'coupon_value' => 'integer',
    'status' => 'boolean'
  ];
  protected $hidden = [
    'created_at', 'updated_at', 'deleted_at'
  ];
  public function malls()
  {
    return $this->belongsToMany('App\Models\Mall', 'campaign_mall', 'campaign_id', 'mall_id');
  }
}
