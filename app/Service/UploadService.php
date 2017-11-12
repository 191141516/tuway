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
use Library\Tools\Common;

/**
 * 文件上传
 * Class UploadService
 * @package App\Service
 */
class UploadService
{
    protected $request;
    protected $img_mime;
    protected $img_size;
    protected $disk_name;
    protected $filename;

    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->img_mime = config('upload.img.mime');
        $this->img_size = config('upload.img.size');
        $this->disk_name = 'tmp';
    }

    /**
     * @param \Illuminate\Config\Repository|mixed $img_mime
     */
    public function setImgMime($img_mime)
    {
        $this->img_mime = $img_mime;
    }

    /**
     * @param \Illuminate\Config\Repository|mixed $img_size
     */
    public function setImgSize($img_size)
    {
        $this->img_size = $img_size;
    }

    /**
     * @param string $disk_name
     */
    public function setDiskName($disk_name)
    {
        $this->disk_name = $disk_name;
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }


    /**
     * 单张图片上传
     */
    public function img($file_name = 'file', $tmp = true)
    {
        if (!$this->request->hasFile($file_name)) {
            throw new \Exception('上传文件为空');
        }

        $file = $this->request->file($file_name);

        if (!$file->isValid()) {
            throw new \Exception('文件上传出错');
        }

        $this->checkMime($file, $this->img_mime);
        $this->checkSize($file, $this->img_size);

        $filename = $this->newFileName($file->guessClientExtension());
        $this->filename = $tmp ? $filename: Common::getFileDir($filename).$filename;

        \Storage::disk($this->disk_name)->putFileAs('', $file, $this->filename);

        return asset(env('UPLOAD_TMP_PATH').$this->filename);
    }

    /**
     * 检查上传文件类型
     */
    protected function checkMime(UploadedFile $file, array $mimes)
    {
        $mime = $file->getClientMimeType();

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
            throw new \Exception('上传文件过大, 请上传大小在'.$this->byteFormat($size).'之内');
        }
    }

    protected function newFileName($extension)
    {
        return md5(uniqid()).".{$extension}";
    }

    protected function byteFormat($bytes) {
        $sizetext = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
        return round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), 2) . $sizetext[$i];
    }
}