<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'media';
    protected $fillable = [
        'file_name', 'file_path', 'mime_type', 'extension', 'file_size'
    ];
    protected $hidden = [
        'created_at',  'updated_at' , 'pivot', 'deleted_at'
    ];
    protected $appends = ['url'];


    protected function getUrlAttribute()
    {

      $url = Storage::disk('uploads')->url($this->file_path . '/' . $this->file_name);
      return $url;
    }
}
