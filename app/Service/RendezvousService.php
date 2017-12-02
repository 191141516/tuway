<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/12/2
 * Time: 11:41
 */

namespace App\Service;


use App\Entities\Activity;
use App\Repositories\RendezvousRepository;

class RendezvousService
{
    /** @var  RendezvousRepository */
    protected $rendezvousRepository;

    public function __construct(RendezvousRepository $rendezvousRepository)
    {
        $this->rendezvousRepository = $rendezvousRepository;
    }

    public function insertRendezvouses(Activity $activity, array $rendezvouses)
    {
        $rows = [];

        $now = now()->format('Y-m-d H:i:s');
        $sort = 1;

        foreach ($rendezvouses as $rendezvous) {
            $rows[] = [
                'activity_id' => $activity->id,
                'rendezvous' => $rendezvous,
                'sort' => $sort,
                'created_at' => $now,
                'updated_at' => $now,
            ];
            $sort++;
        }

        $this->rendezvousRepository->insert($rows);
    }

    public function updateRendezvouses(Activity $activity, array $rendezvouses)
    {
        $activity->rendezvouses()->delete();
        $this->insertRendezvouses($activity, $rendezvouses);
    }

    public function getRendezvousByActivityIdAndId($activity_id, $rendezvous_id)
    {
        return $this->rendezvousRepository->findWhere([
            ['id', '=', $rendezvous_id],
            ['activity_id', '=', $activity_id]
        ]);
    }
}