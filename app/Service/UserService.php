<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/25
 * Time: 下午6:05
 */

namespace App\Service;


use App\Entities\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;

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
}