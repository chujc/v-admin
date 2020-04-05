<?php

namespace ChuJC\Admin\Controllers;

use ChuJC\Admin\Services\AdminMenuService;
use ChuJC\Admin\Support\Result;

class MenusController
{

    /**
     * @var AdminMenuService
     */
    private $service;

    public function __construct(AdminMenuService $service)
    {
        $this->service = $service;
    }

    /**
     * 菜单列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $menus = $this->service->index()->get();
        return Result::data($menus);
    }

    /**
     * 菜单树列表
     * @return \Illuminate\Http\JsonResponse
     */
    public function treeSelect()
    {
        return Result::data($this->service->treeSelect());
    }

    /**
     * 菜单详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        return Result::data($this->service->show($id));
    }

    /**
     * 创建菜单
     * @return \Illuminate\Http\JsonResponse
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        $menu = $this->service->store();
        return Result::data($menu, '创建成功');
    }

    /**
     * 更新菜单
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id)
    {
        if ($this->service->update($id)) {
            return Result::success('更新成功');
        }
        return Result::failed('更新失败');
    }

    /**
     * 删除菜单
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        if ($this->service->destroy($id)) {
            return Result::success('删除成功');
        }
        return Result::failed('删除失败');
    }
}
