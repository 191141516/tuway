<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CreateOperateAccountRequest;
use App\Http\Requests\Admin\UpdateOperateAccountRequest;
use App\Http\Requests\Admin\UserUpdateStatusRequest;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OperateAccountController extends Controller
{
    private $userService;

    private $return = [
        'code' => 200,
        'message' => '',
        'data' => []
    ];

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('admin.operateAccount.index');
    }

    public function ajax(Request $request)
    {
        return $this->userService->datatable($request, true);
    }

    public function updateStatus(UserUpdateStatusRequest $request)
    {
        $this->userService->updateStatus($request);

        return $this->return;
    }

    public function create(CreateOperateAccountRequest $request)
    {
        $this->userService->createOperateAccount($request);

        return $this->return;
    }

    public function detail($id)
    {
        $this->return['data'] = $this->userService->detail($id);
        return $this->return;
    }

    public function update($id, UpdateOperateAccountRequest $request)
    {
        $this->userService->update($id, $request);
        return $this->return;
    }
}
