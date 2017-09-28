<?php

namespace App\Repositories;

use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\EntryRepository;
use App\Entities\Entry;
use App\Validators\EntryValidator;

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
        $this->pushCriteria(app(RequestCriteria::class));
    }
}
