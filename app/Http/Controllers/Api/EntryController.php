<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\EntryCreateRequest;
use App\Http\Requests\EntryListRequest;
use App\Service\EntryService;
use Illuminate\Http\Request;

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

    public function entryList(EntryListRequest $request)
    {
        $data = $this->entryService->entryList($request);
        return $this->success($data);
    }
}
