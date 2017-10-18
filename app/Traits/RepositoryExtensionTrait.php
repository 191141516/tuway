<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/28
 * Time: 下午11:17
 */

namespace App\Traits;


trait RepositoryExtensionTrait
{
    /**
     * 可以批量插入
     * @param $rows
     * @return mixed
     */
    public function insert($rows)
    {
        return $this->model->insert($rows);
    }
}