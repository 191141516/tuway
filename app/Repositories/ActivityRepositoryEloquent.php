<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Entities\Activity;
use App\Traits\RepositoryExtensionTrait;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class ActivityRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ActivityRepositoryEloquent extends BaseRepository implements ActivityRepository
{
    use RepositoryExtensionTrait;
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Activity::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
