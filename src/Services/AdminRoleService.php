<?php

namespace ChuJC\Admin\Services;


use ChuJC\Admin\Models\AdminRole;
use Illuminate\Http\Request;

class AdminRoleService
{
    private $searchField = [
        'username' => 'like',
        'nickname' => 'like',
        'status' => '=',
        'beginTime' => null,
        'endTime' => null,
    ];

    private $validateField = [
        'role_name' => 'string',
        'role_key' => 'string',
        'status' => 'int',
        'remark' => 'string'
    ];

    /**
     * @var AdminRole
     */
    private $model;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.roles_model', AdminRole::class);
        $this->request = $request;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public function index(Request $request)
    {
        $params = $request->only(array_keys($this->searchField));

        return searchModelDateRange(searchModelField($this->model::query(), $params, $this->searchField), $params)->orderBy((new $this->model)->getKeyName());
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->model::whereKey($id)
            ->with('menus')
            ->first();
    }

    /**
     * @return bool|AdminRole|\Illuminate\Database\Eloquent\Model
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        $this->validateField['role_key'] .= "|required";
        $this->validateField['role_name'] .= "|required";
        $params = $this->request->only(array_keys($this->validateField));

        valida($params, $this->validateField);

        \DB::beginTransaction();
        try {
            $role = $this->model::create($params);
            $menuIds = $this->request->input('menuIds');
            if (is_array($menuIds) && count($menuIds) > 0) {
                $role->menus()->sync($menuIds);
            }
            \DB::commit();

        } catch (\Exception $exception) {
            \DB::rollBack();
            report($exception);
            return false;
        }

        return $role;
    }

    /**
     * @param $id
     * @return bool|AdminRole
     * @author john_chu
     */
    public function update($id)
    {
        $params = $this->request->only(array_keys($this->validateField));

        $role = $this->model::findOrFail($id);

        \DB::beginTransaction();
        try {

            $role->update($params);
            $menus = $this->request->input('menus');
            if (is_array($menus) && count($menus) > 0) {
                $role->menus()->sync($menus);
            }
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            report($exception);
            return false;
        }
        return $role;
    }

    /**
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        if ($this->model::whereKey($id)->delete()) {
            return true;
        }
        return false;
    }
}
