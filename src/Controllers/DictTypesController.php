<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Services\AdminDictTypeService;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;

class DictTypesController
{

    /**
     * @var AdminDictTypeService
     */
    private $service;

    public function __construct(AdminDictTypeService $service)
    {
        $this->service = $service;
    }

    /**
     * 字典列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $mode = $request->input('mode');

        $data = $this->service->index();
        if ($mode == 'all') {
            $data = $data->get();
        } else {
            $perPage = $request->input('per_page');
            $data = $data->paginate($perPage);
        }

        return Result::data($data);
    }

    /**
     * 字典详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return Result::data($this->service->show($id));
    }

    /**
     * 新增字典
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ServerExecutionException
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        $dict = $this->service->store();
        if ($dict) {
            return Result::data($dict, '创建成功');
        }

        return Result::failed('创建失败');
    }

    /**
     * 更新字典
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ServerExecutionException
     * @author john_chu
     */
    public function update(int $id)
    {
        if ($this->service->update($id)) {
            return Result::success('修改成功');

        }
        return Result::failed('修改失败');
    }

    /**
     * 删除字典
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ServerExecutionException
     */
    public function destroy($id)
    {
        if (!$this->service->destroy($id)) {
            return Result::success('删除成功');

        }
        return Result::failed('删除失败');
    }

}
