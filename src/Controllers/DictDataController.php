<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Models\AdminDictDatum;
use ChuJC\Admin\Models\AdminDictType;
use ChuJC\Admin\Services\AdminDictDatumService;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;

class DictDataController
{
    /**
     * @var AdminDictDatumService
     */
    private $service;

    public function __construct(AdminDictDatumService $service)
    {
        $this->service = $service;
    }

    /**
     * 字典数据列表
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
     * 字典数据详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return Result::data($this->service->show($id));
    }

    /**
     * 新增字典数据
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ServerExecutionException
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        $dict = $this->service->store();

        if ($dict) {
            return Result::data($dict, '新增成功');
        }
        return Result::failed('新增失败');
    }

    /**
     * 更新字典数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ServerExecutionException
     */
    public function update($id)
    {
        if ($this->service->update($id)) {
            return Result::failed('修改成功');
        }
        return Result::success('修改失败');
    }

    /**
     * 删除字典数据
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($this->service->destroy($id)) {
            return Result::success('删除成功');
        }
        return Result::failed('删除失败');
    }

    /**
     * 通过字典类型获取数据信息
     * @param $dataType
     * @return \Illuminate\Http\JsonResponse
     */
    public function byDictType($dataType)
    {
        $dictDatum = $this->service->byDictType($dataType);
        return Result::data($dictDatum);
    }

}
