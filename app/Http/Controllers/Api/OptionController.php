<?php

namespace App\Http\Controllers\Api;

use App\Service\OptionService;

class OptionController extends ApiController
{
    private $optionService;

    public function __construct(OptionService $optionService)
    {
        $this->optionService = $optionService;
    }

    public function index()
    {
        $data = $this->optionService->all();
        return $this->success($data);
    }
}
