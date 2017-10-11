<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\UserUpdateStatusRequest;
use App\Service\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return view('admin.user.index');
    }

    public function ajax(Request $request)
    {
        return $this->userService->datatable($request);
    }

    public function updateStatus(UserUpdateStatusRequest $request)
    {
        $this->userService->updateStatus($request);

        return [
            'code' => 200,
            'message' => ''
        ];
    }
}
