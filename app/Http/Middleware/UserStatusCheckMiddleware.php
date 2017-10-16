<?php

namespace App\Http\Middleware;

use App\Entities\User;
use Closure;

class UserStatusCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user('api');

        if ($user->status == User::STATUS_BLACK) {
            throw new \Exception('您已被管理员限制权限，如想享受更多权限，请与管理员，微信号“luozituwei”联系');
        }

        return $next($request);
    }
}
