<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/28
 * Time: 下午3:24.
 */

namespace App\Service;

use App\Criteria\Activity\ActivityPaginateCriteria;
use App\Entities\Activity;
use App\Repositories\ActivityRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ActivityService
{
    /** @var ActivityRepository */
    protected $repository;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->repository = $activityRepository;
    }

    public function create(Request $request)
    {
        $row = $request->all();
        $row['pic'] = $this->getImgPath($row['pic']);

        $this->repository->create($row);
    }

    public function paginate(Request $request)
    {
        $this->repository->pushCriteria(app(ActivityPaginateCriteria::class));
        $paginate =  $this->repository->paginate($request->get('page_size', 10));

        foreach ($paginate->items() as $item) {
            $item->append(['start_date_text', 'activity_date_text']);
        }

        return $paginate;
    }

    /**
     * 定时任务调用
     */
    public function updateStatusTask()
    {
        $this->update2Starting();
        $this->update2End();
    }

    /**
     * 更改为活动中.
     */
    protected function update2Starting()
    {
        $where = [
            ['status', '=', Activity::STATUS_APPLYING],
            ['start_date', '<=', Carbon::now()->format('Y-m-d H:i')],
        ];

        $collection = $this->repository->findWhere($where);

        if ($collection->count() > 0) {
            $collection->each(function ($activity){
                $activity->update(['status' => Activity::STATUS_STARTING]);
            });
        }
    }

    /**
     * 更改为结束
     */
    protected function update2End()
    {
        $where = [
            ['status', '=', Activity::STATUS_STARTING],
            ['end_date', '<=', Carbon::now()->format('Y-m-d H:i')],
        ];

        $collection = $this->repository->findWhere($where);

        if ($collection->count()) {
            $collection->each(function ($activity){
                $activity->update(['status' => Activity::STATUS_END]);
            });
        }
    }

    /**
     *
     * @param $pic
     */
    private function getImgPath($pic)
    {
        $url_info = parse_url($pic);

        if(!\File::exists(public_path($url_info['path']))){
            throw new \Exception('图片不存在');
        }

        return $url_info['path'];
    }

    /**
     * @param $id
     * @param $request
     */
    public function detail($id)
    {
        $relations = [
            'user' => function($query){
                $query->select(['id', 'name']);
            },
            'entryUser' => function($query){
                $query->select(['users.avatar_url'])->orderBy('entries.created_at');
            }
        ];

        $activity = $this->repository->with($relations)->find($id);

        $activity->setRelation('entryUser', $activity->getRelation('entryUser')->pluck('avatar_url'));

        return $activity;
    }
}
