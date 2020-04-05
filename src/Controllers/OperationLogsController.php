<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Exports\AdminOperationLogExport;
use ChuJC\Admin\Services\AdminOperationLogService;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class OperationLogsController
{

    /**
     * @var AdminOperationLogService
     */
    private $service;

    public function __construct(AdminOperationLogService $service)
    {
        $this->service = $service;
    }

    /**
     * 操作日志列表
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
     * 导出操作日志
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
                // ALL
                $collection = $this->service->index()->get();
        }
        $filename = date('YmdHis') . 'OperationLogs.xlsx';
        return (new AdminOperationLogExport($collection))->download($filename);
    }

    /**
     * 删除操作日志
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
