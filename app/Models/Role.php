<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
    	'slug' , 'name' , 'permissions'
    ];
    protected $table = 'roles';

    public function users()
    {
    	return $this->belongsToMany( 'App\Models\User' , 'role_users' , 'role_id' , 'user_id' );
    }
    public function getPermissionsAttribute( )
    {
        return json_decode( $this->attributes['permissions'] );
    }
}
