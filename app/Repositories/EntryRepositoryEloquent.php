<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Entities\Entry;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class EntryRepositoryEloquent
 * @package namespace App\Repositories;
 */
class EntryRepositoryEloquent extends BaseRepository implements EntryRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Entry::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
