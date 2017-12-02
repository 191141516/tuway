<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/10/18
 * Time: 21:07
 */

namespace App\Service;


use App\Entities\Activity;
use App\Repositories\ActivityImageRepository;

class ActivityImageService
{
    protected $activityImageRepository;

    public function __construct(ActivityImageRepository $activityImageRepository)
    {
        $this->activityImageRepository = $activityImageRepository;
    }

    public function insertActivityImages(Activity $activity, $images)
    {
        $rows = [];

        $now = now()->format('Y-m-d H:i:s');

        foreach ($images as $image) {
            $rows[] = [
                'activity_id' => $activity->id,
                'img' => $image,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        $this->activityImageRepository->insert($rows);
    }

    public function updateActivityImages(Activity $activity, $images)
    {
        $activity->activityImage()->delete();
        $this->insertActivityImages($activity, $images);
    }
}