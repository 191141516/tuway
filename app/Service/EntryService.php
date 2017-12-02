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
use Validator;

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
        //根据集合点id获取集合点地址
        $data['rendezvous'] = $this->getRendezvouses($activity, $data['rendezvous']);

        $data['user_id'] = $user_id;

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
        if ($activity->total != 0 && $activity->total <= $activity->num) {
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
        $option_collection = $optionService->getInfoByIds($activity->options, ['key', 'rule', 'messages']);

        $rule = [
            'rendezvous' => ['required', 'integer']
        ];

        $messages = [
            'rendezvous.required' => '请选择集合点',
            'rendezvous.integer' => '集合点数据异常',
        ];

        foreach ($option_collection as $option) {
            $rule[$option->key] = $option->rule;
            $messages = array_merge($messages, $option->messages);
        }

        Validator::make($data, $rule, $messages)->validate();
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

    /**
     * 根据集合点id获取集合点地址
     * @param $rendezvous
     */
    private function getRendezvouses(Activity $activity, $rendezvous_id)
    {
        $value = '';

        if ($rendezvous_id != 0) {

            /** @var RendezvousService $rendezvousService */
            $rendezvousService = app(RendezvousService::class);
            $collection = $rendezvousService->getRendezvousByActivityIdAndId($activity->id, $rendezvous_id);

            if ($collection->isEmpty()){
                throw new \Exception('集合点数据异常');
            }

            $rendezvous = $collection->shift();
            $value = $rendezvous['rendezvous'];
        }

        return $value;
    }
}