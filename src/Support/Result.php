<?php

namespace ChuJC\Admin\Support;

use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class Result
{
    /**
     * 返回成功信息
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function success($message = '', $code = 200)
    {
        return new JsonResponse([
            'code' => $code,
            'message' => $message
        ], $code);
    }

    /**
     * 返回成功数据
     * @param $data
     * @param string $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function data($data, $message = '', $code = 200)
    {
        if ($data instanceof LengthAwarePaginator) {
            return new JsonResponse([
                'code' => $code,
                'message' => $message,
                'data' => $data->items(),
                'current_page' => $data->currentPage(),
                'from' => $data->firstItem(),
                'per_page' => $data->perPage(),
                'to' => $data->lastItem(),
                'total' => $data->total(),
            ], $code);
        }

        return response()->json([
            'code' => $code,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * 返回失败信息
     * @param $message
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public static function failed($message, $code = 400)
    {
        return new JsonResponse([
            'code' => $code,
            'message' => $message
        ]);
    }
}
