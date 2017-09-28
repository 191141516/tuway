<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Option extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'name',
        'type',
        'rule',
        'option_value',
    ];

}
