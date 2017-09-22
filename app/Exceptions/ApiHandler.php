<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/22
 * Time: 下午2:24
 */

namespace App\Exceptions;


use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ApiHandler
{
    use ApiResponse;

    /** @var  \Exception */
    protected $exception;

    /** @var  Request */
    protected $request;

    /** @var  string 异常类名 */
    protected $report;

    protected $doReport = [
        AuthenticationException::class => ['未授权', 401],
        ModelNotFoundException::class => ['接口不存在', 404]
    ];

    public function __construct(Request $request, \Exception $exception)
    {
        $this->exception = $exception;
        $this->request = $request;
    }


    /**
     * api设置类型
     * @return bool
     */
    public function shouldReturn()
    {

        if (!($this->request->wantsJson() || $this->request->ajax())) {
            return false;
        }

        foreach (array_keys($this->doReport) as $report) {

            if ($this->exception instanceof $report) {

                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    /**
     *
     * @return mixed
     */
    public function report()
    {
        $message = $this->doReport[$this->report];

        return $this->failed($message[0], $message[1]);

    }
}