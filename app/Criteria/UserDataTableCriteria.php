<?php

namespace App\Criteria;

use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class UserDataTableCriteria
 * @package namespace App\Criteria;
 */
class UserDataTableCriteria implements CriteriaInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param                     $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        $start = $this->request->get('start', config('admin.golbal.list.start')); /*获取开始*/
        $length = $this->request->get('length', config('admin.golbal.list.length')); ///*获取条数*/
        $search_pattern = $this->request->get('search.regex', true); /*是否启用模糊搜索*/
        $columns = $this->request->get('columns', []);
        $orders = $this->request->get('order', []);

        $name = $this->request->get('name' ,'');
        $status = $this->request->get('status' ,'');

        if($name){
            if($search_pattern){
                $model = $model->where('name', 'like', "{$name}%");
            }else{
                $model = $model->where('name', $name);
            }
        }

        if ($status) {
            $model->where('status', $status);
        }

        if($orders){
            $orderName = $columns[$orders['0']['column']]['name'];
            $orderDir = $orders['0']['dir'];
            $model = $model->orderBy($orderName, $orderDir);
        }else{
            $model = $model->orderBy('id', 'desc');
        }

        $page = $start ? floor($start / $length) + 1 : 1;

        Paginator::currentPageResolver(function () use ($page) {
            return $page;
        });

        return $model;
    }
}
