<?php

namespace App\Entities;

use App\Events\UserClearCacheEvent;
use App\Events\UserCreatedEvent;
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
        'status',
        'is_operate',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'session_key',
        'pivot',
    ];

    protected $dispatchesEvents = [
        'saved' => UserCreatedEvent::class,
        'deleted' => UserClearCacheEvent::class,
    ];

    /** 正常 */
    const STATUS_NORMAL = 1;
    /** 拉黑 */
    const STATUS_BLACK = 0;
    /** 运营用户 */
    const OPERATE_USER = 1;
    /** 普通用户 */
    const COMMON_USER = 0;

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

    /**
     * 统计
     */
    public function statistics()
    {
        return $this->hasOne(Statistics::class);
    }

    public function getAvatarUrlAttribute($value)
    {
        if ($this->is_operate == self::OPERATE_USER) {
            return asset(env('UPLOAD_IMG_PATH') . $value);
        } else {
            return $value;
        }
    }
}
