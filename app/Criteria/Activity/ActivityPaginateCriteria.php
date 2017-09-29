<?php

namespace App\Criteria\Activity;

use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class ActivityPaginateCriteria
 * @package namespace App\Criteria\Activity;
 */
class ActivityPaginateCriteria implements CriteriaInterface
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $model->orderBy('status');
        $model->orderBy('start_date');

        return $model;
    }
}
