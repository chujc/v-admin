<?php

namespace ChuJC\Admin\Services;

use ChuJC\Admin\Exceptions\ServerExecutionException;
use ChuJC\Admin\Models\AdminConfig;
use Illuminate\Http\Request;

class AdminConfigService
{
    private $searchField = [
        'config_name' => 'like',
        'config_key' => 'like',
        'is_system' => '=',
//        'beginTime' => '>=',
//        'endTime' => '<=',
    ];

    private $validateField = [
        'config_name' => 'string',
        'config_key' => 'string',
        'config_value' => 'string',
        'is_system' => 'int',
        'remark' => 'string|max:500'
    ];

    /**
     * @var AdminConfig
     */
    private $model;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.configs_model', AdminConfig::class);
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     * @author john_chu
     */
    public function index()
    {
        $params = $this->request->only(array_keys($this->searchField));

        return searchModelDateRange(searchModelField($this->model::query(), $params, $this->searchField), $params)->orderBy((new $this->model)->getKeyName());
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     * @author john_chu
     */
    public function show($id)
    {
        return $this->model::whereKey($id)->first();
    }

    /**
     * @param $configKey
     * @return mixed
     * @author john_chu
     */
    public function byConfigKey($configKey)
    {
        return $this->model::whereConfigKey($configKey)->value('config_value');
    }

    /**
     * @return AdminConfig|\Illuminate\Database\Eloquent\Model
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     * @author john_chu
     */
    public function store()
    {
        $table = config('admin.database.configs_table', 'admin_configs');
        $this->validateField['config_key'] = "required|string|unique:{$table}";
        $this->validateField['config_name'] .= "|required";
        $this->validateField['config_value'] .= "|required";
        $params = $this->request->only(array_keys($this->validateField));

        valida($params, $this->validateField, [
            'config_key.unique' => '参数键名已经存在'
        ]);

        return $this->model::create($params);
    }

    /**
     * @param $id
     * @return bool
     * @throws \ChuJC\Admin\Exceptions\ValidaException|ServerExecutionException
     * @author john_chu
     */
    public function update($id)
    {
        $params = $this->request->only(array_keys($this->validateField));
        valida($params, $this->validateField);

        if (array_key_exists('config_key', $params) && $this->model::where('config_key', $params['config_key'])
                ->whereKeyNot($id)->exists()) {
            throw new ServerExecutionException('相同配置已经存在');
        }
        if ($this->model::whereKey($id)->update($params)) {
            return true;
        }
        return false;
    }

    /**
     * @param $id
     * @return bool
     * @throws ServerExecutionException
     * @author john_chu
     */
    public function destroy($id)
    {
        if (!config('admin.system.config')) {
            if ($this->model::whereKey($id)->value('is_system')) {
                throw new ServerExecutionException('系统内置不能删除');
            }
        }

        if ($this->model::whereKey($id)->delete()) {
            return true;
        }
        return false;
    }

}
