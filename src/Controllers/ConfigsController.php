<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Models\AdminConfig;
use ChuJC\Admin\Services\AdminConfigService;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;

class ConfigsController
{
    /**
     * @var AdminConfigService
     */
    private $service;

    public function __construct(AdminConfigService $service)
    {
        $this->service = $service;
    }

    /**
     * 系统配置列表
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
     * 系统配置详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return Result::data($this->service->show($id));
    }

    /**
     * 通过key获取系统配置
     * @param $configKey
     * @return \Illuminate\Http\JsonResponse
     */
    public function byConfigKey($configKey)
    {
        return Result::data($this->service->byConfigKey($configKey));
    }

    /**
     * 创建系统配置
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        if ($this->service->store()) {
            return Result::success('创建成功');
        }

        return Result::failed('创建失败，请稍后再试！');
    }

    /**
     * 更新系统配置
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ServerExecutionException
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     * @author john_chu
     */
    public function update($id)
    {
        if ($this->service->update($id)) {
            return Result::success('修改成功');
        }

        return Result::failed('更新失败');
    }

    /**
     * 删除系统配置
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $config = AdminConfig::query()->findOrFail($id);

        if ($config->config_type) {
            return Result::failed('系统内置配置不能删除');
        }

        if ($this->service->destroy($id)) {
            return Result::success('删除成功');
        }

        return Result::failed('删除失败');
    }

}
