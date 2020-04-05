<?php

namespace ChuJC\Admin\Exceptions;

use ChuJC\Admin\Support\Result;
use Exception;
use Throwable;

class UnauthorizedException extends Exception
{

    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        $message = 'User does not have the right permission: ' . $message;
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
