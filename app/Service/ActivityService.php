<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/28
 * Time: 下午3:24.
 */

namespace App\Service;

use App\Criteria\Activity\ActivityPaginateCriteria;
use App\Criteria\ActivityDataTableCriteria;
use App\Criteria\UserIdCriteria;
use App\Entities\Activity;
use App\Repositories\ActivityRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Library\Tools\Common;

class ActivityService
{
    /** @var ActivityRepository */
    protected $activityRepository;

    /** @var array 图片移动路径 */
    private $move_img_path = [];

    /** @var array 删除活动图片路径 */
    private $del_img_path = [];

    public function __construct(ActivityRepository $activityRepository)
    {
        $this->activityRepository = $activityRepository;
    }

    public function create(Request $request)
    {
        $row = $request->all();
        $images = $this->transformImg($request->get('images', array()));

        $row['pic'] = reset($images);
        $row['user_id'] = $request->user('api')->id;

        $activity_id = 0;

        \DB::transaction(function () use ($row, &$activity_id, $images) {
            $activity = $this->activityRepository->create($row);
            $activity_id = $activity->id;

            $activityImageService = app(ActivityImageService::class);
            $activityImageService->insertActivityImages($activity_id, $images);

            //移动图片
            $this->moveImg();
            //生成缩略图
            //
        });

        return $activity_id;
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
    private function getImgPath($pic, $to_del = false)
    {
        $url_info = parse_url($pic);

        if (!\File::exists(public_path($url_info['path']))) {
            throw new \Exception('图片不存在');
        }

        $path_info = pathinfo($url_info['path']);

        $dir = Common::getFileDir($path_info['basename']);

        if ($to_del) {
            $this->del_img_path[] = public_path(env('UPLOAD_IMG_PATH').$dir.$path_info['basename']);
        }else{
            $this->move_img_path[] = [
                'from' => public_path($url_info['path']),
                'to' => public_path(env('UPLOAD_IMG_PATH').$dir.$path_info['basename'])
            ];
        }


        return $dir.$path_info['basename'];
    }

    /**
     * @param $id
     * @param $request
     */
    public function detail($id)
    {
        $activity = $this->getActivityDetail($id);

        $user_id = \Auth::guard('api')->user()->id;

        $activity->setAttribute('edit', $activity->user_id == $user_id);

        $entryUser = $activity->getRelation('entryUser');
        $activityImage = $activity->getRelation('activityImage');

        $activity->setAttribute('is_entry', in_array($user_id, $entryUser->pluck('id')->toArray()));

        $activity->append(['detail_activity_date_text']);

        $activity->setRelation('entryUser', $entryUser->pluck('avatar_url'));

        $activity->setRelation('activity_image', $activityImage->pluck('img'));

        return $activity;
    }

    public function getInfoById($id, $columns = ['*'])
    {
        return $this->activityRepository->find($id, $columns);
    }

    /**
     * 修改活动
     * @param $id
     * @param Request $request
     * @throws \Exception
     */
    public function edit($id, Request $request)
    {
        $row = $request->all();
        $activity = $this->activityRepository->find($id);

        if (empty($activity)) {
            throw new \Exception('活动不存在');
        }

        if ($activity->status != Activity::STATUS_APPLYING) {
            throw new \Exception('当前状态活动不能修改');
        }

        $images_arr = $request->get('images', array());
        $images = $this->updateImages($images_arr);

        $update_pic = false;
        $old_pic = $activity->pic;

        if ($activity->pic != reset($images_arr)) {
            $row['pic'] = reset($images);
            $update_pic = true;
        }else{
            unset($row['pic']);
        }

        \DB::transaction(function () use ($activity, $row, $update_pic, $old_pic, $images) {

            $activityImageService = app(ActivityImageService::class);
            $activityImageService->updateActivityImages($activity, $images);

            $activity->update($row);

            if ($update_pic) {
                $this->getImgPath($old_pic, true);
            }

            $this->moveImg();
            $this->delImg();
        });
    }

    /**
     * 用户删除活动
     * @param $id
     */
    public function userDelete($id)
    {
        $this->activityRepository->pushCriteria(app(UserIdCriteria::class));
        $this->delete($id);
    }

    /**
     * 删除活动
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        $activity = $this->activityRepository->find($id);

        if (empty($activity)) {
            throw new \Exception('活动不存在');
        }

        if ($activity->status != Activity::STATUS_APPLYING) {
            throw new \Exception('当前状态活动不能删除');
        }

        \DB::transaction(function () use ($activity) {
            $activity->entry()->delete();
            $activity->delete();
        });
    }

    /**
     * 获取必填项
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function option($id)
    {
        $activity = $this->activityRepository->find($id);

        if (empty($activity)) {
            throw new \Exception('活动不存在');
        }

        /** @var OptionService $optionService */
        $optionService = app(OptionService::class);
        return $optionService->getInfoByIds($activity->options, ['id', 'name', 'key', 'type', 'option_value', 'placeholder']);
    }


    public function datatable(Request $request)
    {
        $this->activityRepository->pushCriteria(app(ActivityDataTableCriteria::class));
        $data = $this->activityRepository->paginate($request->get('length'));

        $count = $data->total();

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data->items(),
        ];
    }

    /**
     * 更改state
     * @param Request $request
     * @throws \Exception
     */
    public function updateState(Request $request)
    {
        $activity_id = $request->get('activity_id');
        $state = $request->get('state');

        $activity = $this->activityRepository->find($activity_id);

        if (empty($activity)) {
            throw new \Exception('活动不存在');
        }

        $activity->state = $state;
        $activity->save();
    }

    /**
     * @param $id
     * @return mixed
     */
    private function getActivityDetail($id)
    {
        $relations = [
            'user' => function ($query) {
                $query->select(['id', 'name', 'avatar_url']);
            },
            'entryUser' => function ($query) {
                $query->select(['users.avatar_url', 'users.id'])->orderBy('entries.created_at');
            },
            'activityImage' => function($query) {
                $query->select(['activity_id', 'img']);
            }
        ];

        $activity = $this->activityRepository->with($relations)->find($id);
        return $activity;
    }


    public function info($id)
    {
        $activity = $this->getActivityDetail($id);

        $entryUser = $activity->getRelation('entryUser');

        $activity->setRelation('entryUser', $entryUser->pluck('avatar_url'));
        return $activity;
    }


    /**
     * 活动图片
     * @param $images
     * @return array
     */
    private function transformImg($images)
    {
        $paths = [];

        if (!empty($images)) {
            foreach ($images as $image) {
                $paths[] = $this->getImgPath($image);
            }
        }

        return $paths;
    }

    /**
     * 移动图片
     */
    private function moveImg()
    {
        foreach ($this->move_img_path as $item) {
            Common::move($item['from'], $item['to']);
        }
    }

    private function delImg()
    {
        foreach ($this->del_img_path as $path) {
            Common::delFile($path);
        }
    }

    private function updateImages($images)
    {
        $paths = [];
        if ($images) {
            foreach ($images as $image) {

                $url_info = parse_url($image);

                if (!\File::exists(public_path($url_info['path']))) {
                    throw new \Exception('图片不存在');
                }

                $path_info = pathinfo($url_info['path']);

                $dir = Common::getFileDir($path_info['basename']);

                $dirs = explode('/', $path_info['dirname']);
                //判断是tmp还是img
                if ($dirs['2'] == 'tmp') {
                    $this->move_img_path[] = [
                        'from' => public_path($url_info['path']),
                        'to' => public_path(env('UPLOAD_IMG_PATH').$dir.$path_info['basename'])
                    ];

                }

                $paths[] = $dir.$path_info['basename'];
            }
        }

        return $paths;
    }
}
