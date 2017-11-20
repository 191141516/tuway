<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Service\ActivityService;
use App\Service\UserService;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    protected $return = [
        'code' => 200,
        'message' => '',
        'data' => [],
    ];

    private $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }


    public function index()
    {
        $userService = app(UserService::class);
        $accounts = $userService->getAllOperateAccount();
        return view('admin.activity.index', compact('accounts'));
    }

    public function ajax(Request $request)
    {
        return $this->activityService->datatable($request);
    }

    public function updateState(Request $request)
    {
        $this->validate($request, [
            'activity_id' => 'required',
            'state' => 'required|in:0,1'
        ]);

        $this->activityService->updateState($request);

        return $this->return;
    }

    /**
     * 删除活动
     * @param $id
     * @return mixed
     */
    public function destroy($id)
    {
        $this->activityService->adminDelete($id);
        return $this->return;
    }

    /**
     * 活动详情
     * @param $id
     */
    public function info($id)
    {
        try{
            $this->return['data'] =  $this->activityService->info($id);
        }catch (\Exception $e) {
            $this->return['code'] = 500;
            $this->return['message'] = $e->getMessage();
        }

        return $this->return;
    }

    /**
     * 活动置顶
     * @param $id
     */
    public function top($id)
    {
        $this->activityService->top($id);
        return $this->return;
    }

    /**
     * 取消置顶
     * @param $id
     * @return array
     */
    public function cancelTop($id)
    {
        $this->activityService->cancelTop($id);
        return $this->return;
    }
}
