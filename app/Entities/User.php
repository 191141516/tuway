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
        'pivot',
    ];

    /**
     * 发布的活动
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class, 'user_id');
    }

    /**
     * 参数的活动
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(Entry::class, 'user_id');
    }
}
