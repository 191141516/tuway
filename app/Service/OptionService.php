<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/29
 * Time: 下午1:48.
 */

namespace App\Service;

use App\Repositories\OptionRepository;

class OptionService
{
    /** @var OptionRepository */
    protected $optionRepository;

    public function __construct(OptionRepository $optionRepository)
    {
        $this->optionRepository = $optionRepository;
    }

    /**
     * @param array $ids
     */
    public function getInfoByIds(array $ids, $columns = ['*'])
    {
        return $this->optionRepository->findWhereIn('id', $ids, $columns);
    }

    public function all()
    {
        $data = $this->optionRepository->all();

        $data->each(function($item){
            $item->addHidden(['rule', 'messages', 'created_at', 'updated_at']);
        });

        return $data;
    }
}
