<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/29
 * Time: 下午12:02
 */

namespace App\Service;


use App\Entities\Activity;
use App\Repositories\EntryRepository;
use Illuminate\Http\Request;

class EntryService
{
    /** @var EntryRepository  */
    protected $entryRepository;

    public function __construct(EntryRepository $entryRepository)
    {
        $this->entryRepository = $entryRepository;
    }

    public function create(Request $request)
    {
        $activity_id = $request->get('activity_id');
        $user_id = $request->user('api')->id;
        $data = $request->all();

        //check报名
        $this->checkUserActivityEntry($activity_id, $user_id);

        $activityService = app(ActivityService::class);
        $activity = $activityService->getInfoById($activity_id);

        //check报名数量
        $this->checkActivityTotal($activity);
        //validator报名必填项
        $this->validatorOptions($data, $activity);

        $data['user_id'] = $user_id;

        //报名，以后改为队列方式，防止超报
        \DB::transaction(function () use($data, $activity){
            $this->entryRepository->create($data);
            $activity->num++;
            $activity->save();
        });

    }

    private function checkUserActivityEntry($activity_id, $user_id)
    {
        $result = $this->entryRepository->findWhere([['activity_id' , '=', $activity_id], ['user_id', '=', $user_id]], ['id']);
        if ($result->count() > 0) {
            throw new \Exception('一个微信号只能报名一次');
        }
    }

    private function checkActivityTotal(Activity $activity)
    {
        if ($activity->total <= $activity->num) {
            throw new \Exception('活动报名人数已满');
        }
    }

    /**
     * @param Request $request
     * @param $activity
     */
    private function validatorOptions(array $data, $activity)
    {
        $optionService = app(OptionService::class);
        $option_collection = $optionService->getInfoByIds($activity->options, ['key', 'rule']);

        $rule = [];

        foreach ($option_collection as $option) {
            $rule[$option->key] = $option->rule;
        }

        validator($data, $rule);
    }

    /**
     * 活动报名列表
     * @param $id
     * @param $request
     */
    public function entryList(Request $request)
    {
        $page_size = $request->get('page_size', 10);
        $activity_id = $request->get('activity_id');

        $relations = [
            'user' => function($query){
                $query->select(['id', 'avatar_url', 'name']);
            }
        ];

        $this->entryRepository->scopeQuery(function($query) use ($activity_id){
            return $query->where('activity_id', $activity_id);
        });

        return $this->entryRepository->with($relations)->paginate($page_size);
    }
}