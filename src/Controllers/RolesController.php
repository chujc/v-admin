<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Services\AdminRoleService;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;

class RolesController
{

    /**
     * @var AdminRoleService
     */
    private $service;

    public function __construct(AdminRoleService $service)
    {
        $this->service = $service;
    }

    /**
     * 角色列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page');
        $select = $request->input('select');

        $data = $this->service->index($request);

        if ($select) {
            return Result::data($data->get());
        }

        $data = $data->paginate($perPage);

        return Result::data($data);
    }

    /**
     * 角色详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return Result::data($this->service->show($id));
    }


    /**
     * 创建角色
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
     * 更新角色
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        if ($id == 1) {
            return Result::failed('超级管理角色不能被修改');
        }

        if ($this->service->update($id)) {
            return Result::success('修改成功');
        }

        return Result::failed('修改失败');
    }

    /**
     * 删除角色
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($id == 1) {
            return Result::failed('超级管理角色不能被删除');
        }

        if ($this->service->destroy($id)) {
            return Result::success('删除成功');
        }

        return Result::failed('删除失败');
    }

}
