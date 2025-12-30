<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cartalyst\Sentinel\Users\EloquentUser as SentinelUser;

class Auth extends SentinelUser
{
    protected $fillable = [
        'email',
        'username',
        'password',
        'last_name',
        'first_name',
        'permissions',
        'mall_id',
        'status',
    ];

    protected $loginNames = ['email', 'username'];

    protected $casts = [
        'mall_id' => 'integer',
        'status' => 'boolean'
    ];
}
