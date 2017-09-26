<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/25
 * Time: 下午6:05
 */

namespace App\Service;

use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use Library\Tools\ResponseFind;
use Library\Wechat\Decode\WXBizDataCrypt;

class UserService
{
    /** @var UserRepository  */
    protected $repository;

    public function __construct(UserRepository $userRepository)
    {
        $this->repository = $userRepository;
    }

    /**
     * 创建用户或者更新用户信息
     * @param $row
     * @return mixed
     */
    public function updateOrCreate($row)
    {
        $attributes = [
            'open_id' => $row['open_id']
        ];

        return $this->repository->updateOrCreate($attributes, $row);
    }

    /**
     * 创建accessToken
     * @param User $user
     * @return string
     */
    public function createToken(User $user)
    {
        $user->tokens()->delete();
        $createToken = $user->createToken($user->id);

        $createToken->token->expires_at = Carbon::now()->addDays(15);
        $createToken->token->save();

        return $createToken->accessToken;
    }

    /**
     * 保存微信信息
     * @param $encrypted_data
     * @param $iv
     * @param User $user
     * @throws \Exception
     */
    public function saveWXInfo($encrypted_data, $iv, User $user)
    {
        $app_id = env('WX_APPID');
        $session_key = $user->session_key;

        //解密
        $crypt = new WXBizDataCrypt($app_id, $session_key);
        $err_code = $crypt->decryptData($encrypted_data, $iv, $data);

        if ($err_code != 0) {
            throw new \Exception('数据异常');
        }

        $dataObj = new ResponseFind($data);

        $user->name = $dataObj->find('nickName');
        $user->gender = $dataObj->find('gender');
        $user->city = $dataObj->find('city');
        $user->province = $dataObj->find('province');
        $user->country = $dataObj->find('country');
        $user->avatar_url = $dataObj->find('avatarUrl');
        $user->union_id = empty($user->union_id) ? $dataObj->find('unionId'): $user->union_id;
        $user->save();
    }
}