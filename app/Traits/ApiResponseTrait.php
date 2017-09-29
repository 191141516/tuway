<?php

namespace App\Traits;

use Response;
use Symfony\Component\HttpFoundation\Response as Foundationresponse;

/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/22
 * Time: 下午1:44
 */
trait ApiResponseTrait
{
    protected $status = Foundationresponse::HTTP_OK;

    /** @var array 返回的数据固定格式 */
    protected $returnData = [
        'code' => Foundationresponse::HTTP_OK,
        'message' => '',
        'errors' => array(),
        'data' => array(),
    ];


    /**
     * @param array $header
     * @return mixed
     */
    public function respond($header = [])
    {
        return Response::json($this->returnData, $this->status, $header);
    }

    /**
     * @param $message
     * @param int $code
     * @param string $status
     * @return mixed
     */
    public function failed($message, $code = Foundationresponse::HTTP_BAD_REQUEST, $data = [], $errors = [], $header = [])
    {
        $this->returnData['message'] = $message;
        $this->returnData['data'] = $data;
        $this->returnData['code'] = $code;
        $this->returnData['errors'] = $errors;

        return $this->respond($header);
    }

    /**
     * @param $message
     * @return mixed
     */
    public function message($message, $header = [])
    {
        $this->returnData['message'] = $message;
        return $this->respond($header);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function internalError($message = "Internal Error!")
    {
        $this->returnData['message'] = $message;
        $this->returnData['code'] = Foundationresponse::HTTP_INTERNAL_SERVER_ERROR;
        return $this->respond();
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function created($message = "created")
    {
        $this->returnData['message'] = $message;
        $this->returnData['code'] = Foundationresponse::HTTP_CREATED;

        return $this->respond();
    }

    /**
     * @param $data
     * @param string $status
     * @return mixed
     */
    public function success($data = [], $header = [])
    {
        $this->returnData['data'] = $data;
        return $this->respond($header);
    }

    /**
     * @param string $message
     * @return mixed
     */
    public function notFond($message = 'Not Fond!', $header = [])
    {
        $this->returnData['message'] = $message;
        $this->returnData['code'] = Foundationresponse::HTTP_NOT_FOUND;
        return $this->respond($header);
    }
}