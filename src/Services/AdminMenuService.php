<?php

namespace ChuJC\Admin\Services;


use ChuJC\Admin\Exceptions\ServerExecutionException;
use ChuJC\Admin\Models\AdminMenu;
use Illuminate\Http\Request;

class AdminMenuService
{
    private $searchField = [
        'menu_name' => 'like',
        'is_visible' => '=',
    ];

    private $validateField = [
        'menu_name' => 'required',
        'parent_id' => 'required'
    ];

    /**
     * @var AdminMenu
     */
    private $model = AdminMenu::class;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.menus_model', AdminMenu::class);
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public function index()
    {
        $params = $this->request->only([
            'menu_name',
            'is_visible'
        ]);

        return searchModelField($this->model::query(), $params, $this->searchField)->orderBy('order');
    }

    /**
     * @return array
     */
    public function treeSelect()
    {
        $adminMenu = $this->model::query()->get();

        $tree = arrayToTree($adminMenu->toArray(), 'menu_id');

        return $tree;
    }

    /**
     * @param $id
     * @return AdminMenu|null
     */
    public function show($id)
    {
        return $this->model::find($id);
    }

    /**
     * @return AdminMenu|\Illuminate\Database\Eloquent\Model
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        $params = $this->request->only([
            'menu_name',
            'parent_id',
            'order',
            'path',
            'component',
            'is_link',
            'menu_type',
            'is_visible',
            'permission',
            'icon',
            'remark'
        ]);

        valida($params, $this->validateField);

        return $this->model::create($params);
    }

    /**
     * @param $id
     * @return bool
     */
    public function update($id)
    {
        $menu = $this->model::findOrFail($id);

        $params = $this->request->only([
            'menu_name',
            'parent_id',
            'order',
            'path',
            'component',
            'is_link',
            'menu_type',
            'is_visible',
            'permission',
            'icon',
            'remark'
        ]);

        if ($menu->getKey() == $params['parent_id']) {
            $params['parent_id'] = 0;
        }

        if ($menu->update($params)) {
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function destroy($id)
    {
        $menu = $this->model::findOrFail($id);
        if ($this->model::where('parent_id', $menu->getKey())->count()) {
            throw new ServerExecutionException('请先删除子菜单');
        }
        if ($menu->delete()) {
            return true;
        }
        return false;
    }
}
