<?php

namespace App\Entities;

use App\Events\EntryCreatedEvent;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Entry extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'activity_id',
        'user_id',
        'name',
        'phone',
        'id_card',
        'gender',
        'age',
    ];

    protected $dispatchesEvents = [
        'created' => EntryCreatedEvent::class
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
