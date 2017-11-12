<?php

namespace App\Http\Controllers\Admin;

use App\Service\UploadService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends Controller
{
    use ApiResponseTrait;

    private $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function img()
    {
        $this->uploadService->setImgMime(config('upload.avatar.mime'));
        $this->uploadService->setImgSize(config('upload.avatar.size'));

        $filename = $this->uploadService->img();

        return $this->success(['url' => $filename]);
    }
}
