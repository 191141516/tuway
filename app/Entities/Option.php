<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Library\Tools\Common;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Option extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'name',
        'key',
        'type',
        'rule',
        'option_value',
        'messages',
        'placeholder'
    ];

    public function getOptionValueAttribute($value)
    {
        return empty($value) ? '[]': Common::jsonDecode($value);
    }

    public function getRuleAttribute($value)
    {
        return empty($value) ? '[]': Common::jsonDecode($value);
    }

    public function getMessagesAttribute($value)
    {
        return empty($value) ? '[]': Common::jsonDecode($value);
    }
}
