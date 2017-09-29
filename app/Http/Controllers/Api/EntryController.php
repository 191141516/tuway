<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EntryCreateRequest;
use App\Service\EntryService;

class EntryController extends ApiController
{
    /** @var EntryService  */
    private $entryService;

    public function __construct(EntryService $entryService)
    {
        $this->entryService = $entryService;
    }

    /**
     * 报名
     * @param EntryCreateRequest $request
     * @return mixed
     */
    public function create(EntryCreateRequest $request)
    {
        $this->entryService->create($request);
        return $this->success();
    }
}
