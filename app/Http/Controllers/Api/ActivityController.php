<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityCreateRequest;
use App\Http\Requests\ActivityUpdateRequest;
use App\Service\ActivityService;
use Illuminate\Http\Request;

class ActivityController extends ApiController
{
    /** @var ActivityService  */
    private $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    /**
     * 活动列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data = $this->activityService->paginate($request);
        return $this->success($data);
    }

    /**
     * 发布活动
     * @param ActivityCreateRequest $activityCreateRequest
     * @return mixed
     */
    public function create(ActivityCreateRequest $activityCreateRequest)
    {
        $this->activityService->create($activityCreateRequest);
        return $this->success();
    }

    /**
     * 活动详情
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $data = $this->activityService->detail($id);
        return $this->success($data);
    }

    public function edit($id, ActivityUpdateRequest $request)
    {
        $this->activityService->edit($id, $request);
        return $this->success();
    }
}
