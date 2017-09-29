<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/29
 * Time: 下午12:02
 */

namespace App\Service;


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

        //check报名
        $this->checkUserActivityEntry($activity_id, $user_id);

        $activityService = app(ActivityService::class);
        $activity = $activityService->getInfoById($activity_id);

        $optionService = app(OptionService::class);
        $option_collection = $optionService->getInfoByIds($activity->options, ['key', 'rule']);

        $rule = [];

        foreach ($option_collection as $option) {
            $rule[$option->key] = $option->rule;
        }

        $data = $request->all();

        validator($data, $rule);

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
}