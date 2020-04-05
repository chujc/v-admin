<?php

namespace ChuJC\Admin\Services;

use ChuJC\Admin\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserService
{
    private $searchField = [
        'username' => 'like',
        'nickname' => 'like',
        'status' => '=',
//        'beginTime' => '>=',
//        'endTime' => '<=',
    ];

    private $validateField = [
        'password' => 'string|max:50',
        'nickname' => 'string|max:20',
        'avatar' => 'string|max:200',
        'status' => 'int',
        'remark' => 'string|max:500'
    ];

    /**
     * @var AdminUser
     */
    private $model;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.users_model', AdminUser::class);
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @author john_chu
     */
    public function index()
    {
        $params = $this->request->only(array_keys($this->searchField));

        return searchModelDateRange(searchModelField($this->model::query(), $params, $this->searchField), $params)
            ->with('roles:role_name')
            ->orderBy((new $this->model)->getKeyName());
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     * @author john_chu
     */
    public function show($id)
    {
        return $this->model::whereKey($id)
            ->with('roles')
            ->first();
    }

    /**
     * @return bool|AdminUser
     * @throws \Exception
     * @author john_chu
     */
    public function store()
    {
        $table = config('admin.database.users_table', 'admin_users');
        $this->validateField['username'] = "required|unique:{$table}";
        $this->validateField['nickname'] .= "|required";
        $this->validateField['password'] .= "|required";
        $params = $this->request->only(array_keys($this->validateField));

        valida($params, $this->validateField, [
            'username.unique' => '账号已经存在'
        ]);

        $params['password'] = Hash::make($params['password']);

        \DB::beginTransaction();
        try {
            $adminUser = $this->model::create($params);
            $this->syncRoles($adminUser);
            \DB::commit();
            return $adminUser;
        } catch (\Exception $exception) {
            \DB::rollBack();
            report($exception);
        }
        return false;
    }

    /**
     * @param $id
     * @return bool|AdminUser
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function update($id)
    {
        $params = $this->request->only(array_keys($this->validateField));
        valida($params, $this->validateField);

        $adminUser = $this->model::findOrFail($id);

        if (array_key_exists('password', $params)) {
            $params['password'] = Hash::make($params['password']);
        }

        $syncRolesStatus = $this->syncRoles($adminUser);
        $updateStatus = $adminUser->update($params);
        if ($syncRolesStatus || $updateStatus) {
            return $adminUser;
        }

        return false;
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

    /**
     * 同步管理员对应角色
     * @param AdminUser $adminUser
     * @return bool
     * @author john_chu
     */
    private function syncRoles(AdminUser $adminUser): bool
    {
        $roles = $this->request->input('roles');
        if (is_array($roles)) {
            if (count($roles) == 0) {
                $adminUser->roles()->detach();
            } else {
                $adminUser->roles()->sync($roles);
            }
            return true;
        }
        return false;
    }
}
