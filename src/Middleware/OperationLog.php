<?php

namespace ChuJC\Admin\Middleware;

use Carbon\Carbon;
use ChuJC\Admin\Facades\Admin;
use ChuJC\Admin\Models\AdminOperationLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class OperationLog
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, \Closure $next)
    {

        $ip2region = new \Ip2Region();

        try {
            $info = $ip2region->btreeSearch($request->getClientIp());
        } catch (\Exception $exception) {
            report($exception);
            $info['region'] = '未知地址';
        }

        if ($this->shouldLogOperation($request)) {
            $log = [
                'method' => $request->method(),
                'url' => substr($request->path(), 0, 255),
                'ip' => $request->getClientIp(),
                'location' => $info['region'],
                'params' => json_encode($request->input()),
                'browser' => getBrowseInfo(),
                'os' => getOS(),
                'http_user_agent' => $request->server('HTTP_USER_AGENT'),
                'oper_name' => Admin::user()->username,
            ];

            try {
                $log = AdminOperationLog::create($log);
                $request->oper_id = $log->getKey();
            } catch (\Exception $exception) {
                // pass
            }
        }

        return $next($request);
    }

//    /**
//     * @param $request
//     * @param JsonResponse $response
//     * @author john_chu
//     */
//    public function terminate($request, $response)
//    {
////        if (!config('admin.operation_log.result_log') && $request->oper_id && $response instanceof JsonResponse) {
////            return $response;
////        }
////
////        try {
////            $updateData = [
////                'result' =>  property_exists($response, 'original') ? $response->original : '',
////                'status' =>  $response->getStatusCode(),
////            ];
////
////            if (property_exists($response, 'exception') && $response->exception) {
////                $updateData['message'] = $response->exception->getMessage();
////            } else {
////                if (method_exists($response, 'getData')) {
////                    $data = $response->getData();
////                    if ($data instanceof \stdClass) {
////                        $updateData['status'] = $data->status ?? $response->getStatusCode();
////                        $updateData['message'] = $data->message ?? '';
////                    }
////                }
////            }
////            AdminOperationLog::whereKey($request->oper_id)
////                ->update($updateData);
////        }catch (\Exception $exception) {
////            report($exception);
////        }
//    }
    /**
     * @param Request $request
     *
     * @return bool
     */
    protected function shouldLogOperation(Request $request)
    {
        return config('admin.operation_log.enable')
            && !$this->inExceptArray($request)
            && $this->inAllowedMethods($request->method())
            && Admin::user();
    }

    /**
     * Whether requests using this method are allowed to be logged.
     *
     * @param string $method
     *
     * @return bool
     */
    protected function inAllowedMethods($method)
    {
        $allowedMethods = collect(config('admin.operation_log.allowed_methods'))->filter();

        if ($allowedMethods->isEmpty()) {
            return true;
        }

        return $allowedMethods->map(function ($method) {
            return strtoupper($method);
        })->contains($method);
    }

    /**
     * Determine if the request has a URI that should pass through CSRF verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function inExceptArray($request)
    {
        foreach (config('admin.operation_log.except') as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            $methods = [];

            if (Str::contains($except, ':')) {
                list($methods, $except) = explode(':', $except);
                $methods = explode(',', $methods);
            }

            $methods = array_map('strtoupper', $methods);

            if ($request->is($except) &&
                (empty($methods) || in_array($request->method(), $methods))) {
                return true;
            }
        }

        return false;
    }
}
