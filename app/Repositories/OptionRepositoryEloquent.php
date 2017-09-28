<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Entities\Option;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class OptionRepositoryEloquent
 * @package namespace App\Repositories;
 */
class OptionRepositoryEloquent extends BaseRepository implements OptionRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Option::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
