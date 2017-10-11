<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Entities\Admin;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class AdminRepositoryEloquent
 * @package namespace App\Repositories;
 */
class AdminRepositoryEloquent extends BaseRepository implements AdminRepository, CacheableInterface
{
    use CacheableRepository;

    protected $cacheMinutes = 90;

    protected $cacheOnly = ['all', 'paginate'];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Admin::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
