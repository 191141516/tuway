<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class TestController extends ApiController
{
    public function index(Request $request)
    {
//        $cache = app(config('repository.cache.repository', 'cache'));
//
//
//        $keys = CacheKeys::getKeys(ActivityRepositoryEloquent::class);
//
//        foreach ($keys as $key) {
//            $cache->forget($key);
//        }
//
//        dd(CacheKeys::getKeys(ActivityRepositoryEloquent::class));
//
//
//
//        $url = 'http://tuway.quoyle.info/upload/img/07/52/0752c845188d991445e4f57981fb9559.png';
//
//        $url_info = parse_url($url);
//
//        $path_info = pathinfo($url_info['path']);
//
//        dd($path_info);

//        $entryService = app(EntryService::class);
//        $data = $entryService->entryList($request);
//        return $this->success($data);

//        $user =  User::find(1);
//
//        $user->tokens()->delete();
//        $createToken = $user->createToken($user->id);
//
//        $createToken->token->expires_at = Carbon::now()->addDays(15);
//        $createToken->token->save();
//
//        return $this->success(['token' => $createToken->accessToken]);
    }
}
