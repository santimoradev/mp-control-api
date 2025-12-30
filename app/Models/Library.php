<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Library extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'library';
    protected $fillable = [
        'name', 'pathname', 'extension'
    ];
    protected $hidden = [
        'created_at',  'updated_at' , 'pivot', 'deleted_at'
    ];
    protected $appends = ['url'];


    protected function getUrlAttribute()
    {

        $url = Storage::disk('uploads')->url($this->pathname);

        return $url;
    }
}
