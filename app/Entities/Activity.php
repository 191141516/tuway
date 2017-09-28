<?php

namespace App\Entities;

use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Activity extends Model implements Transformable
{
    use TransformableTrait;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'pic',
        'total',
        'phone',
        'price',
        'address',
        'options',
        'num',
        'start_date',
        'end_date',
    ];

    /**
     * 发布者
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(User::class);
    }

    /**
     * 报名信息
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function entry()
    {
        return $this->belongsTo(Entry::class, 'activity_id');
    }

    /**
     * 所有报名的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entryUser()
    {
        return $this->belongsToMany(User::class, 'entries', 'activity_id', 'user_id');
    }
}
