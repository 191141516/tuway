<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Traits\RepositoryExtensionTrait;
use Prettus\Repository\Eloquent\BaseRepository;
use App\Entities\ActivityImage;

/**
 * Class ActivityImageRepositoryEloquent
 * @package namespace App\Repositories;
 */
class ActivityImageRepositoryEloquent extends BaseRepository implements ActivityImageRepository
{
    use RepositoryExtensionTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return ActivityImage::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
