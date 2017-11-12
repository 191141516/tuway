<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/25
 * Time: 下午6:05
 */

namespace App\Service;

use App\Criteria\UserDataTableCriteria;
use App\Repositories\UserRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Library\Tools\ResponseFind;
use Library\Wechat\Decode\WXBizDataCrypt;

class UserService
{
    /** @var UserRepository  */
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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

        return $this->userRepository->updateOrCreate($attributes, $row);
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

    public function datatable(Request $request, $isOperate = false)
    {
        $is_operate = $isOperate ? \App\Entities\User::OPERATE_USER: \App\Entities\User::COMMON_USER;

        $this->userRepository->scopeQuery(function ($query) use($is_operate){
            return $query->where('is_operate', $is_operate);
        });

        $this->userRepository->pushCriteria(app(UserDataTableCriteria::class));
        $data = $this->userRepository->with(['statistics'])->paginate($request->get('length'));

        $count = $data->total();

        return [
            'draw' => $request->get('draw'),
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $data->items(),
        ];
    }

    public function updateStatus(Request $request)
    {
        $user_id = $request->get('user_id');
        $status = $request->get('status');

        /** @var \App\Entities\User $user */
        $user = $this->userRepository->find($user_id);
        $user->status = $status;

        $user->save();
    }

    public function createOperateAccount(Request $request)
    {
        /** @var ImageService $imageService */
        $imageService = app(ImageService::class);
        $paths = $imageService->transformImg($request->get('avatar_url'));

        $row = $request->all();
        $row['avatar_url'] = reset($paths);
        $row['is_operate'] = \App\Entities\User::OPERATE_USER;
        $row['open_id'] = '';
        $row['session_key'] = '';
        $row['expires_in'] = now();

        $imageService->moveImg();
        return $this->userRepository->create($row);
    }

    public function detail($id)
    {
        return $this->userRepository->find($id);
    }

    public function update($id, Request $request)
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            throw new \Exception('用户不存在');
        }

        /** @var ImageService $imageService */
        $imageService = app(ImageService::class);
        $paths = $imageService->updateImages($request->get('avatar_url'));

        $user->avatar_url = reset($paths);
        $user->name = $request->get('name');

        $user->save();
        $imageService->moveImg();
        $imageService->delImg();
    }
}