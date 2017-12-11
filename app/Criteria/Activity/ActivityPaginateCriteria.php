<?php

namespace App\Criteria\Activity;

use App\Entities\Activity;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ActivityPaginateCriteria
 * @package namespace App\Criteria\Activity;
 */
class ActivityPaginateCriteria implements CriteriaInterface
{
    private $request;

    private static $screening_map = [
        Activity::TYPE_MY,
        Activity::TYPE_JOIN
    ];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $screening = $this->request->get('screening');

        if (in_array($screening, self::$screening_map)) {
            $user = $this->request->user('api');
            switch ($screening) {
                case Activity::TYPE_MY:

                    $model->where('user_id', '=', $user->id);
                    break;

                case Activity::TYPE_JOIN:

                    $model->join('entries', function ($join) use ($user){
                        $join->on('activities.id', '=', 'entries.activity_id')
                             ->where('entries.user_id', '=', $user->id);
                    });
                    break;
            }
        }

        $model->where('state', '=', Activity::STATE_NORMAL);
        $model->select('activities.*');
        $model->orderBy('activities.top_time', 'desc');
        $model->orderBy('activities.status');
        $model->orderByRaw("CASE WHEN activities.status in (1, 2) THEN activities.start_date END ASC, CASE WHEN status=3 THEN activities.start_date END DESC");

        return $model;
    }
}
