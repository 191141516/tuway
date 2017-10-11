<?php

namespace App\Http\Controllers\Api;

use App\Entities\Option;
use App\Service\EntryService;
use App\Service\UploadService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TestController extends ApiController
{
    public function index(Request $request)
    {
        $uploadService = app(UploadService::class);

        $filename = $uploadService->img();
        return $this->success(['url' => $filename]);

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
