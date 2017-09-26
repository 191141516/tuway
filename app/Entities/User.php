<?php

namespace App\Entities;

use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class User extends \App\User implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'open_id',
        'expires_in',
        'session_key',
        'gender',
        'city',
        'province',
        'country',
        'avatar_url',
        'union_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'session_key',
    ];
}
