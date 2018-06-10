<?php

namespace App\Http\Controllers\Api;

use App\Service\UserService;
use Illuminate\Http\Request;

class UserController extends ApiController
{
    private $service;

    public function __construct(UserService $userService)
    {
        $this->service = $userService;
    }

    public function saveUserInfo(Request $request)
    {
        $this->validate($request, [
            'iv' => 'required',
            'encryptedData' => 'required'
        ]);

        $user = $request->user('api');

        \Log::info($request->get('encryptedData'));
        \Log::info($request->get('iv'));
        \Log::info($user);

        $this->service->saveWXInfo($request->get('encryptedData'), $request->get('iv'), $user);

        return $this->success();
    }
}
