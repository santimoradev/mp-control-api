<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;
use App\Models\Balance;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $fillable = [
      'email',
      'username',
      'password',
      'last_name',
      'first_name',
      'permissions',
      'status'
    ];

    protected $hidden = [
        'password','created_at','updated_at', 'deleted_at' ,'pivot'
    ];
    protected $loginNames = [
        'email',
        'username'
    ];
    protected $casts = [
        'status' => 'integer'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
    public function roles()
    {
    	return $this->belongsToMany( 'App\Models\Role' , 'role_users' , 'user_id' , 'role_id' );
    }
}
