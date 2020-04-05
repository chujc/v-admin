<?php

namespace ChuJC\Admin\Exceptions;

use ChuJC\Admin\Support\Result;
use Exception;
use Throwable;

class ServerExecutionException extends Exception
{
    public function __construct($message = "", $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * 报告异常
     *
     * @return void
     */
    public function report()
    {
    }

    /**
     * 转换异常为 HTTP 响应
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return Result::failed($this->message, $this->code);
    }

}
