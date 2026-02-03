<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Business extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'businesses';
    protected $fillable = [
        'name', 'media_id', 'status'
    ];

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id');
    }
}
