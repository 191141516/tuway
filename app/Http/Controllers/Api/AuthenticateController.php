<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/22
 * Time: 下午2:06.
 */

namespace App\Http\Controllers\Api;

use App\Service\UserService;
use App\Service\WeiXinService;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthenticateController extends ApiController
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'logout',
        ]);
    }

    // 退出登录
    public function logout(Request $request)
    {
        if (Auth::guard('api')->check()) {
            Auth::guard('api')->user()->token()->revoke();
        }

        return $this->message('退出登录成功');
    }

    /**
     * 微信登录
     * @param Request $request
     * @return mixed
     */
    public function wxLogin(Request $request)
    {
        $this->validate($request, [
            'code' => 'required'
        ]);

        $result = WeiXinService::getSessionKey($request->get('code'));
        $result['open_id'] = $result['openid'];
        $result['union_id'] = isset($result['unionid'])? $result['unionid']: '';
        $result['expires_in'] = Carbon::now()->addSecond($result['expires_in']);

        $userService = app(UserService::class);

        $user = $userService->updateOrCreate($result);

        $token = $userService->createToken($user);

        return $this->success(['token' => $token]);
    }
}
