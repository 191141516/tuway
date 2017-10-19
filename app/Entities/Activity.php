<?php

namespace App\Entities;

use App\Events\PublishActivityEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;

class Activity extends Model implements Transformable
{
    use TransformableTrait;

    /** 报名中 */
    const STATUS_APPLYING = 1;
    /** 进行中 */
    const STATUS_STARTING = 2;
    /** 结束 */
    const STATUS_END = 3;


    /** 我发布的 */
    const TYPE_MY = 1;
    /** 我参与的 */
    const TYPE_JOIN = 2;


    /** 正常 */
    const STATE_NORMAL = 1;
    /** 拉黑 */
    const STATE_BLACK = 0;


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
        'status',
        'state',
    ];

    protected $week = [
        '星期日',
        '星期一',
        '星期二',
        '星期三',
        '星期四',
        '星期五',
        '星期六',
    ];

    protected $dispatchesEvents = [
        'created' => PublishActivityEvent::class
    ];

    /**
     * 发布者
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 报名信息
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function entry()
    {
        return $this->hasMany(Entry::class, 'activity_id', 'id');
    }

    /**
     * 所有报名的用户
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entryUser()
    {
        return $this->belongsToMany(User::class, 'entries', 'activity_id', 'user_id');
    }

    /**
     * 活动图片
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityImage()
    {
        return $this->hasMany(ActivityImage::class, 'activity_id', 'id');
    }

    public function getStartDateTextAttribute()
    {
        $now = Carbon::now();
        $createDate = new Carbon($this->created_at);
        $diff_hours = $now->diffInHours($createDate);

        if ($diff_hours < 1) {
            $diff_minutes = $now->diffInMinutes($createDate);
            $text = $diff_minutes . '分钟前';
        } else if ($diff_hours < env('DIFF_HOURS')) {
            $text = $diff_hours . '小时前';
        } else {
            $text = $createDate->format('m/d H:i');
        }

        return $text;
    }

    public function getActivityDateTextAttribute()
    {
        $start_date = new Carbon($this->start_date);
        $end_date = new Carbon($this->end_date);
        $format = 'Ymd';
        $show_format = 'm月d日';

        if ($start_date->format($format) === $end_date->format($format)) {
            $week_str = $start_date->format($show_format) . ' ' . $this->week[$start_date->dayOfWeek];
        } else {
            $week_str = $start_date->format($show_format) . '-' . $end_date->format($show_format);
        }

        return $week_str;
    }

    public function getDetailActivityDateTextAttribute()
    {
        $start_date = new Carbon($this->start_date);
        $end_date = new Carbon($this->end_date);
        $format = 'Ymd';
        $show_format = 'm月d日';
        $time_format = 'H:i';

        if ($start_date->format($format) === $end_date->format($format)) {
            $week_str = $start_date->format($show_format) . ' ' . $this->week[$start_date->dayOfWeek];
        } else {
            $week_str = $start_date->format($show_format) . '(' . $this->week[$start_date->dayOfWeek] . ')' . $start_date->format($time_format)
                . '-' . $end_date->format($show_format) . '(' . $this->week[$end_date->dayOfWeek] . ')' . $end_date->format($time_format);
        }

        return $week_str;
    }

    public function setOptionsAttribute($value)
    {
        $arr = is_array($value) ? $value : [$value];
        $this->attributes['options'] = json_encode($arr);
    }

    public function getOptionsAttribute($value)
    {
        return json_decode($value);
    }

    public function getPicAttribute($value)
    {
        return asset(env('UPLOAD_IMG_PATH') . $value);
    }
}
