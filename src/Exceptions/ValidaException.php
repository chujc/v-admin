<?php

namespace ChuJC\Admin\Exceptions;

use ChuJC\Admin\Support\Result;
use Exception;
use Throwable;

class ValidaException extends Exception
{
    public function __construct($message = "", $code = 422, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * 转换异常为 HTTP 响应
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return Result::failed($this->message, $this->code)->setStatusCode($this->code);
    }

}
