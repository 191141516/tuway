<?php

namespace App\Console\Commands;

use File;
use Illuminate\Console\Command;

/**
 * 删除上传的临时图片,30分钟过期
 * Class ClearTmpImg
 * @package App\Console\Commands
 */
class ClearTmpImg extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clear:tmp-img';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '删除上传的临时图片';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //FilesystemIterator;
        $files = File::files(public_path(env('UPLOAD_TMP_PATH')));

        $file_paths = [];

        foreach ($files as $file) {
            if ($file->getMTime() + 1800 < time()) {
                $file_paths[] = $file->getRealPath();
            }
        }

        $file_paths && File::delete($file_paths);
    }
}
