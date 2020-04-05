<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Services\AdminUserService;
use ChuJC\Admin\Support\Result;
use Illuminate\Http\Request;

class AdminUsersController
{

    /**
     * @var AdminUserService
     */
    private $service;

    public function __construct(AdminUserService $service)
    {
        $this->service = $service;
    }

    /**
     * 管理员列表
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
     * 管理员详情
     * @param int $id 管理员id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $id)
    {
        return Result::data($this->service->show($id));
    }

    /**
     * 创建管理员
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store()
    {
        if ($this->service->store()) {
            return Result::success('创建成功');
        }

        return Result::failed('创建失败，请稍后再试！');
    }

    /**
     * 更新管理员
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(int $id)
    {
        if ($id == 1) {
            return Result::failed('超级管理员不能被修改');
        }

        if ($this->service->update($id)) {
            return Result::success('修改成功');
        }

        return Result::failed('修改失败');
    }

    /**
     * 删除管理员
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        if ($id == 1) {
            return Result::failed('超级管理员不能被删除');
        }

        if ($this->service->destroy($id)) {
            return Result::success('删除成功');
        }

        return Result::failed('删除失败');
    }

}
