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
use Library\Tools\Common;

class ActivityService
{
    /** @var ActivityRepository */
    protected $activityRepository;

    /** @var string 图片tmp路径 */
    private $from;

    /** @var string 图片移动路径 */
    private $to;

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function create(Request $request)
    {
        $row = $request->all();
        $row['pic'] = $this->getImgPath($row['pic']);
        $row['user_id'] = $request->user('api')->id;

        \DB::transaction(function () use ($row) {
            $this->activityRepository->create($row);
            //移动图片
            Common::move($this->from, $this->to);
            //生成缩略图
            //
        });
    }

    public function paginate(Request $request)
    {
        $relations = [
            'user' => function($query){
                $query->select(['id', 'name', 'avatar_url']);
            },
        ];

        $this->activityRepository->pushCriteria(app(ActivityPaginateCriteria::class));
        $paginate = $this->activityRepository->with($relations)->paginate($request->get('page_size', 10));

        foreach ($paginate->items() as $item) {
            $item->append(['start_date_text', 'activity_date_text']);
            $item->addHidden(['options', 'content', 'phone', 'user_id', 'created_at', 'updated_at']);
        }

        return $paginate;
    }

    /**
     * 定时任务调用.
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

        $collection = $this->activityRepository->findWhere($where);

        if ($collection->count() > 0) {
            $collection->each(function ($activity) {
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

        $collection = $this->activityRepository->findWhere($where);

        if ($collection->count()) {
            $collection->each(function ($activity) {
                $activity->update(['status' => Activity::STATUS_END]);
            });
        }
    }

    /**
     * @param $pic
     */
    private function getImgPath($pic)
    {
        $url_info = parse_url($pic);

        if (!\File::exists(public_path($url_info['path']))) {
            throw new \Exception('图片不存在');
        }

        $path_info = pathinfo($url_info['path']);

        $dir = Common::getFileDir($path_info['basename']);

        $this->from = public_path($url_info['path']);
        $this->to = public_path(env('UPLOAD_IMG_PATH').$dir.$path_info['basename']);

        return $dir.$path_info['basename'];
    }

    /**
     * @param $id
     * @param $request
     */
    public function detail($id)
    {
        $relations = [
            'user' => function ($query) {
                $query->select(['id', 'name']);
            },
            'entryUser' => function ($query) {
                $query->select(['users.avatar_url'])->orderBy('entries.created_at');
            },
        ];

        $activity = $this->activityRepository->with($relations)->find($id);

        $activity->setAttribute('edit', $activity->user_id == \Auth::guard('api')->user()->id);

        $activity->setRelation('entryUser', $activity->getRelation('entryUser')->pluck('avatar_url'));

        return $activity;
    }

    public function getInfoById($id, $columns = ['*'])
    {
        return $this->activityRepository->find($id, $columns);
    }

    public function edit($id, Request $request)
    {
        $row = $request->all();
        $activity = $this->activityRepository->find($id);

        if (empty($activity)) {
            throw new \Exception('活动不存在');
        }

        $update_pic = false;
        $old_pic = $activity->pic;
        if ($activity->pic != $row['pic']) {
            $row['pic'] = $this->getImgPath($row['pic']);
            $update_pic = true;
        }else{
            unset($row['pic']);
        }

        \DB::transaction(function () use ($activity, $row, $update_pic, $old_pic) {
            $activity->update($row);

            if ($update_pic) {

                Common::move($this->from, $this->to);

                $this->getImgPath($old_pic);
                Common::delFile($this->to);
            }
        });
    }

}