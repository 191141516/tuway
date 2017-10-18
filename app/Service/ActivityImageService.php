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

    public function insertActivityImages($activity_id, $images)
    {
        $rows = [];

        foreach ($images as $image) {
            $rows[] = [
                'activity_id' => $activity_id,
                'img' => $image,
            ];
        }

        $this->activityImageRepository->insert($rows);
    }

    public function updateActivityImages(Activity $activity, $images)
    {
        $activity->activityImage()->delete();
        $this->insertActivityImages($activity->id, $images);
    }
}