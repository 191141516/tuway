<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Entities\Activity;
use App\Traits\RepositoryExtensionTrait;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class ActivityRepositoryEloquent.
 */
class ActivityRepositoryEloquent extends BaseRepository implements ActivityRepository, CacheableInterface
{
    use RepositoryExtensionTrait,CacheableRepository;

    /**
     * Specify Model class name.
     *
     * @return string
     */
    public function model()
    {
        return Activity::class;
    }

    /**
     * Boot up the repository, pushing criteria.
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
