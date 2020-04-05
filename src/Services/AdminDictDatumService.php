<?php

namespace ChuJC\Admin\Services;


use ChuJC\Admin\Exceptions\ServerExecutionException;
use ChuJC\Admin\Models\AdminDictDatum;
use Illuminate\Http\Request;

class AdminDictDatumService
{
    private $searchField = [
        'dict_type' => '=',
        'dict_label' => 'like',
        'status' => '='
    ];

    private $validateField = [
        'dict_label' => 'required',
        'dict_value' => 'required',
        'status' => 'int',
        'remark' => 'string'
    ];

    /**
     * @var AdminDictDatum
     */
    private $model = AdminDictDatum::class;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.dict_data_model', AdminDictDatum::class);
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public function index()
    {
        $params = $this->request->only([
            'dict_label',
            'dict_type',
            'status'
        ]);

        return searchModelField($this->model::query(), $params, $this->searchField)->orderBy((new $this->model)->getKeyName());
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function show($id)
    {
        return $this->model::whereKey($id)->first();
    }

    /**
     * @return AdminDictDatum|\Illuminate\Database\Eloquent\Model
     * @throws ServerExecutionException
     * @throws \ChuJC\Admin\Exceptions\ValidaException
     */
    public function store()
    {
        $params = $this->request->only([
            'dict_label',
            'dict_value',
            'dict_type',
            'dict_order',
            'is_default',
            'status',
            'remark',
        ]);

        valida($params, $this->validateField);

        if ($this->model::where('dict_type', $params['dict_type'])
            ->where('dict_value', $params['dict_value'])
            ->exists()
        ) {
            throw new ServerExecutionException('字典数据已经存在');
        }

        return $this->model::create($params);
    }


    /**
     * @param $id
     * @return int
     * @throws ServerExecutionException
     */
    public function update($id)
    {
        $params = $this->request->only([
            'dict_label',
            'dict_value',
            'dict_type',
            'dict_order',
            'is_default',
            'status',
            'remark',
        ]);

        if (array_key_exists('dict_label', $params)) {
            if ($this->model::where('dict_value', $params['dict_value'])
                ->where('dict_type', $params['dict_type'])
                ->whereKeyNot($id)->exists()) {
                throw new ServerExecutionException('字典数据已经存在');
            }
        }
        return $this->model::whereKey($id)->update($params);
    }

    /**
     * @param $id
     * @return bool
     * @throws ServerExecutionException
     * @author john_chu
     */
    public function destroy($id)
    {
        if (!config('admin.system.dict_data')) {
            if ($this->model::whereKey($id)->value('is_system')) {
                throw new ServerExecutionException('系统内置不能删除');
            }
        }
        if ($this->model::whereKey($id)->delete()) {
            return true;
        }
        return false;
    }

    /**
     * @param $dataType
     * @return \Illuminate\Support\Collection
     */
    public function byDictType($dataType)
    {
        return $this->model::query()->whereDictType($dataType)
            ->where('status', 1)
            ->orderBy('dict_order')
            ->get();
    }
}
