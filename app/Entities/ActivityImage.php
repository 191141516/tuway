<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class ActivityImage extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'activity_id',
        'img'
    ];

    public function getImgAttribute($value)
    {
        return asset(env('UPLOAD_IMG_PATH').$value);
    }
}
