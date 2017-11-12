<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/11/12
 * Time: 14:46
 */

namespace App\Service;


use Library\Tools\Common;

class ImageService
{
    /** @var array 图片移动路径 */
    private $move_img_path = [];

    /** @var array 删除图片路径 */
    private $del_img_path = [];

    /**
     * 图片处理
     * @param $images
     * @return array
     */
    public function transformImg($images)
    {
        $paths = [];

        if (!empty($images)) {

            if (is_array($images)) {
                foreach ($images as $image) {
                    $paths[] = $this->getImgPath($image);
                }
            }else{
                $paths[] = $this->getImgPath($images);
            }

        }

        return $paths;
    }

    /**
     * 移动图片
     */
    public function moveImg()
    {
        foreach ($this->move_img_path as $item) {
            Common::move($item['from'], $item['to']);
        }
    }

    public function delImg()
    {
        foreach ($this->del_img_path as $path) {
            Common::delFile($path);
        }
    }

    public function updateImages($images)
    {
        $paths = [];
        if ($images) {
            if (is_array($images)) {
                foreach ($images as $image) {
                    $path = $this->update($image);
                    $paths[] = $path;
                }
            }else{
                $path = $this->update($images);
                $paths[] = $path;
            }
        }

        return $paths;
    }

    /**
     * @param $pic
     */
    public function getImgPath($pic, $to_del = false)
    {
        $url_info = parse_url($pic);

        if (!\File::exists(public_path($url_info['path']))) {
            throw new \Exception('图片不存在');
        }

        $path_info = pathinfo($url_info['path']);

        $dir = Common::getFileDir($path_info['basename']);

        $path = $dir.$path_info['basename'];
        if ($to_del) {
            $this->del_img_path[] = public_path(env('UPLOAD_IMG_PATH').$path);
        }else{
            $this->move_img_path[] = [
                'from' => public_path($url_info['path']),
                'to' => public_path(env('UPLOAD_IMG_PATH').$path)
            ];
        }


        return $path;
    }

    /**
     * @param $image
     * @return string
     * @throws \Exception
     */
    private function update($image)
    {
        $url_info = parse_url($image);

        if (!\File::exists(public_path($url_info['path']))) {
            throw new \Exception('图片不存在');
        }

        $path_info = pathinfo($url_info['path']);

        $dir = Common::getFileDir($path_info['basename']);

        $path = $dir . $path_info['basename'];

        $dirs = explode('/', $path_info['dirname']);
        //判断是tmp还是img
        if ($dirs['2'] == 'tmp') {
            $this->move_img_path[] = [
                'from' => public_path($url_info['path']),
                'to' => public_path(env('UPLOAD_IMG_PATH') . $path)
            ];

        }
        return $path;
    }
}