<?php

namespace App\Http\Controllers\Api;

use App\Service\UploadService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadController extends ApiController
{
    private $uploadService;

    public function __construct(UploadService $uploadService)
    {
        $this->uploadService = $uploadService;
    }

    public function img()
    {
        $filename = $this->uploadService->img();
        return $this->success(['url' => $filename]);
    }
}
