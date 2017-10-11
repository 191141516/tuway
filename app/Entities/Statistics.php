<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Statistics extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'user_id',
        'join',
        'publish',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
