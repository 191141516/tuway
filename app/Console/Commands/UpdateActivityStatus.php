<?php

namespace App\Console\Commands;

use App\Service\ActivityService;
use Illuminate\Console\Command;

class UpdateActivityStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:activity-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新活动状态';

    /** @var ActivityService  */
    private $activityService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ActivityService $activityService)
    {
        parent::__construct();
        $this->activityService = $activityService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->activityService->updateStatusTask();
    }
}
