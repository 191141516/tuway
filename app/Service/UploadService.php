<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/28
 * Time: 下午6:38
 */

namespace App\Service;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

/**
 * 文件上传
 * Class UploadService
 * @package App\Service
 */
class UploadService
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * 单张图片上传
     */
    public function img()
    {
        if (!$this->request->hasFile('file')) {
            throw new \Exception('上传文件为空');
        }

        $file = $this->request->file('file');

        if (!$file->isValid()) {
            throw new \Exception('文件上传出错');
        }

        $this->checkMime($file, config('upload.img.mime'));
        $this->checkSize($file, config('upload.img.size'));

        $filename = \Storage::disk('tmp')->putFile('', $file);

        return asset(env('UPLOAD_TMP_PATH').$filename);
    }

    /**
     * 检查上传文件类型
     */
    protected function checkMime(UploadedFile $file, array $mimes)
    {
        $mime = $file->getMimeType();

        if (!in_array($mime, $mimes)) {
            throw new \Exception('上传文件类型错误');
        }
    }

    /**
     * 检查上传文件的大小
     */
    protected function checkSize(UploadedFile $file, $size)
    {
        $file_size = $file->getSize();

        if ($file_size > $size) {
            throw new \Exception('上传文件过大');
        }
    }

    protected function newFileName($extension)
    {
        return md5(uniqid()).".{$extension}";
    }
}