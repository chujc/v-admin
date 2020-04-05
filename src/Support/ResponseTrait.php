<?php


namespace ChuJC\Admin\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Trait ResultTrait
 * @package ChuJC\Admin\Support
 */
trait ResponseTrait
{
    protected $statusCode = JsonResponse::HTTP_OK;


    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }


    public function stored($data, $message = '创建成功')
    {
        return $this->setStatusCode(JsonResponse::HTTP_CREATED)->respond($data, $message);
    }


    public function updated($data, $message = '更新成功')
    {
        return $this->setStatusCode(JsonResponse::HTTP_OK)->respond($data, $message);
    }


    public function deleted($message = '删除成功')
    {
        return $this->setStatusCode(JsonResponse::HTTP_NO_CONTENT)->respond([], $message);
    }


    public function accepted($message = '请求已接受，等待处理')
    {
        return $this->setStatusCode(JsonResponse::HTTP_ACCEPTED)->message($message);
    }


    public function notFound($message = '您访问的资源不存在')
    {
        return $this->failed($message, JsonResponse::HTTP_NOT_FOUND);
    }


    public function internalError($message = '未知错误导致请求失败')
    {
        return $this->failed($message, JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
    }


    public function failed($message, $code = JsonResponse::HTTP_BAD_REQUEST)
    {
        return $this->message($message, $code);
    }

    public function success($data)
    {
        return $this->respond($data);
    }


    public function message($message, $code = JsonResponse::HTTP_OK)
    {
        return $this->setStatusCode($code)->respond([], $message);
    }

    public function respond($data = [], $message = '请求成功', array $header = [])
    {
        if ($data instanceof LengthAwarePaginator) {
            return new JsonResponse([
                'code' => $this->statusCode,
                'message' => $message,
                'data' => $data->items(),
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ], $this->statusCode, $header, JSON_UNESCAPED_UNICODE);
        }
        return new JsonResponse([
            'code' => $this->statusCode,
            'message' => $message,
            'data' => $data ? $data : []
        ], $this->statusCode, $header, JSON_UNESCAPED_UNICODE);
    }
}
