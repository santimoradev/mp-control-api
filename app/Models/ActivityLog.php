<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
  use HasFactory;
  protected $table = 'activity_logs';
  protected $fillable = [
    'user_id',
    'action_name',
    'module_name',
    'title',
    'description',
    'before_data',
    'after_data',
    'ip_address',
    'user_agent'
  ];
  protected $casts = [
    'user_id' => 'integer'
  ];
}
