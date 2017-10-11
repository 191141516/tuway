<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Entities\Statistics;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;

/**
 * Class StatisticsRepositoryEloquent
 * @package namespace App\Repositories;
 */
class StatisticsRepositoryEloquent extends BaseRepository implements StatisticsRepository, CacheableInterface
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
        return Statistics::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
