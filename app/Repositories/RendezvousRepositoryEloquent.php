<?php

namespace App\Repositories;

use App\Criteria\PatchCriteria;
use App\Entities\Rendezvous;
use App\Traits\RepositoryExtensionTrait;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class RendezvousRepositoryEloquent
 * @package namespace App\Repositories;
 */
class RendezvousRepositoryEloquent extends BaseRepository implements RendezvousRepository
{
    use RepositoryExtensionTrait;

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Rendezvous::class;
    }

    

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(PatchCriteria::class));
    }
}
