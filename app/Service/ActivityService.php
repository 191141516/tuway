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

    /** @var ImageService  */
    protected $imageService;

    public function __construct(ActivityRepository $activityRepository, ImageService $imageService)
    {
        $this->activityRepository = $activityRepository;
        $this->imageService = $imageService;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    private function getActivityById($id)
    {
        $activity = $this->activityRepository->find($id);

        if (empty($activity)) {
            throw new \Exception('活动不存在');
        }
        return $activity;
    }

    public function create(Request $request)
    {
        $row = $request->all();
        $images = $this->imageService->transformImg($request->get('images'));

        $row['pic'] = reset($images);
        $row['user_id'] = $request->user('api')->id;
        $this->imageService->addThumbPath(public_path(env('UPLOAD_IMG_PATH').$row['pic']));

        $activity_id = 0;

        \DB::transaction(function () use ($row, &$activity_id, $images) {
            $activity = $this->activityRepository->create($row);
            $activity_id = $activity->id;

            $activityImageService = app(ActivityImageService::class);
            $activityImageService->insertActivityImages($activity_id, $images);

            //移动图片
            $this->imageService->moveImg();
            //生成缩略图
            $this->imageService->generateThumb();
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

        $collection = $this->activityRepository->findWhere($where, ['id']);

        if ($collection->count()) {
            $ids = $collection->pluck('id')->toArray();
            $this->updateByIds($ids, ['status' => Activity::STATUS_STARTING]);
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

        $collection = $this->activityRepository->findWhere($where, ['id']);

        if ($collection->count()) {
            $ids = $collection->pluck('id')->toArray();
            $this->updateByIds($ids, ['status' => Activity::STATUS_END, 'top_time' => 0]);
        }
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

//        $activity->setRelation('entryUser', $entryUser);

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
        $activity = $this->getActivityById($id);

        if ($activity->status != Activity::STATUS_APPLYING) {
            throw new \Exception('当前状态活动不能修改');
        }

        $images_arr = $request->get('images', array());
        $images = $this->imageService->updateImages($images_arr);

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
                $this->imageService->getImgPath($old_pic, true);
            }

            $this->imageService->moveImg();
            $this->imageService->delImg();
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
        $activity = $this->getActivityById($id);

        if ($activity->status != Activity::STATUS_APPLYING) {
            throw new \Exception('当前状态活动不能删除');
        }

        \DB::transaction(function () use ($activity) {
            $images = $activity->activityImage;
            $activity->entry()->delete();
            $activity->delete();
            $this->removeImage($images);
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
                $query->select(['users.avatar_url', 'users.id', 'users.name'])->orderBy('entries.created_at');
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
     * 后台删除
     * @param $id
     * @throws \Exception
     */
    public function adminDelete($id)
    {
        $activity = $this->getActivityById($id);

        \DB::transaction(function () use ($activity) {
            $images = $activity->activityImage;
            $activity->entry()->delete();
            $activity->delete();
            $this->removeImage($images);
        });
    }

    /**
     * 置顶
     * @param $id
     */
    public function top($id)
    {
        $activity = $this->getActivityById($id);

        $activity->top_time = time();
        $activity->save();
    }

    /**
     * 取消置顶
     * @param $id
     */
    public function cancelTop($id)
    {
        $activity = $this->getActivityById($id);

        $activity->top_time = 0;
        $activity->save();
    }

    /**
     * @param $images
     * @param $this
     */
    public function removeImage($images)
    {
        foreach ($images as $image) {

            $this->imageService->getImgPath($image->img, true);
        }

        $this->imageService->delImg();
    }

    public function updateByIds(array $ids, array $update)
    {
        Activity::whereIn('id', $ids)->update($update);
    }
}
