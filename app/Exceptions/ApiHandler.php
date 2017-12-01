<?php
/**
 * Created by PhpStorm.
 * User: quoyle
 * Date: 2017/9/22
 * Time: 下午2:24
 */

namespace App\Exceptions;


use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiHandler
{
    use ApiResponseTrait;

    /** @var  \Exception */
    protected $exception;

    /** @var  Request */
    protected $request;

    /** @var  string 异常类名 */
    protected $report;

    protected $doReport = [
        AuthenticationException::class => ['未授权', 401],
        NotFoundHttpException::class => ['接口不存在', 404],
        ModelNotFoundException::class => ['系统异常', 500],
        ValidationException::class => ['数据验证错误', 601],
        MethodNotAllowedHttpException::class => ['接口不存在', 404],

        Exception::class => ['操作失败', 602],                      //要放在最后
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

        $errors = [];

        if (method_exists($this->exception, 'errors')) {
            $errors = $this->exception->errors();
        }

        $exception_message = $this->exception->getMessage();
        $exception_message = empty($exception_message)  ? $message[0] : $exception_message;
        return $this->failed($exception_message, $message[1], [], $errors);

    }
}