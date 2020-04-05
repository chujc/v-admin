<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Exports\AdminLoginLogExport;
use ChuJC\Admin\Services\AdminLoginLogService;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LoginLogsController
{

    /**
     * @var AdminLoginLogService
     */
    private $service;

    public function __construct(AdminLoginLogService $service)
    {
        $this->service = $service;
    }

    /**
     * 登录日志
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page');

        $data = $this->service->index()->paginate($perPage);

        return Result::data($data);
    }

    /**
     * 导出登录日志
     * @param Request $request
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export(Request $request)
    {
        $command = $request->input('command');

        switch (strtoupper($command)) {
            case 'PAGE':
                $perPage = $request->input('per_page');
                $collection = Collection::make($this->service->index()->paginate($perPage)->items());
                break;
            case 'SELECT':
                $collection = $this->service->selectIds()->get();
                break;
            default:
                //ALL
                $collection = $this->service->index()->get();
        }
        $filename = date('YmdHis') . 'LoginLogs.xlsx';
        return (new AdminLoginLogExport($collection))->download($filename);
    }

    /**
     * 删除登录日志
     * @param $ids
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($ids)
    {
        if ($this->service->destroy($ids)) {
            return Result::success('删除成功');
        }

        return Result::failed('删除失败');
    }
}
