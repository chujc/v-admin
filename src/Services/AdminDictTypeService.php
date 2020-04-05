<?php

namespace ChuJC\Admin\Services;


use ChuJC\Admin\Exceptions\ServerExecutionException;
use ChuJC\Admin\Models\AdminDictType;
use Illuminate\Http\Request;

class AdminDictTypeService
{
    private $searchField = [
        'dict_name' => 'like',
        'dict_type' => 'like',
        'status' => '=',
//        'beginTime' => '>=',
//        'endTime' => '<=',
    ];

    private $validateField = [
        'dict_name' => 'required',
        'dict_type' => 'required',
        'status' => 'int',
        'remark' => 'string'
    ];

    /**
     * @var AdminDictType
     */
    private $model = AdminDictType::class;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.dict_types_model', AdminDictType::class);
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public function index()
    {
        $params = $this->request->only([
            'dict_name',
            'dict_type',
            'status',
            'beginTime',
            'endTime',
        ]);

        return searchModelDateRange(searchModelField($this->model::query(), $params, $this->searchField), $params)->orderBy((new $this->model)->getKeyName());
    }


    public function show($id)
    {
        return $this->model::whereKey($id)
            ->with('dictDatum')
            ->first();
    }

    /**
     * @return AdminDictType|\Illuminate\Database\Eloquent\Model
     * @throws ServerExecutionException
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        $params = $this->request->only([
            'dict_name',
            'dict_type',
            'status',
            'remark',
        ]);

        valida($params, $this->validateField);

        if ($this->model::where('dict_type', $params['dict_type'])->exists()) {
            throw new ServerExecutionException('字典类型已经存在');
        }

        return $this->model::create($params);
    }

    /**
     * @param $id
     * @return bool
     * @throws ServerExecutionException
     */
    public function update($id)
    {
        $params = $this->request->only([
            'dict_name',
            'dict_type',
            'status',
            'remark',
        ]);
        $dict = $this->model::query()->findOrFail($id);

        \DB::beginTransaction();
        if (array_key_exists('dict_type', $params)) {
            if ($this->model::where('dict_type', $params['dict_type'])->whereKeyNot($id)->exists()) {
                throw new ServerExecutionException('字典类型已经存在');
            }
            $dict->dictDatum()->update(['dict_type' => $params['dict_type']]);
        }
        try {
            $dict->update($params);
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollBack();
            report($exception);
            return false;
        }
        return true;
    }

    /**
     * @param $id
     * @return bool
     * @throws ServerExecutionException
     */
    public function destroy($id)
    {
        $dict = $this->model::query()->with('dictDatum')->findOrFail($id);

        if (!config('admin.system.config')) {
            if ($dict->is_system) {
                throw new ServerExecutionException('系统内置不能删除');
            }
        }

        if ($dict->dictDatum->count()) {
            throw new ServerExecutionException('请先删除字典数据');
        }
        if ($dict->delete()) {
            return true;
        }
        return false;
    }
}
