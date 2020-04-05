<?php

namespace ChuJC\Admin\Services;


use ChuJC\Admin\Models\AdminLoginLog;
use ChuJC\Admin\Models\AdminUser;
use Illuminate\Http\Request;

class AdminLoginLogService
{
    private $searchField = [
        'username' => 'like',
        'ip' => 'like',
        'status' => '=',
//        'beginTime' => '>=',
//        'endTime' => '<=',
    ];

    /**
     * @var AdminLoginLog
     */
    private $model = AdminLoginLog::class;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->model = config('admin.database.login_logs_model', AdminLoginLog::class);
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder|mixed
     */
    public function index()
    {
        $params = $this->request->only(array_keys($this->searchField));

        return searchModelDateRange(searchModelField($this->model::query(), $params, $this->searchField), $params)->orderByDesc((new $this->model)->getKeyName());
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function selectIds()
    {
        $ids = $this->request->input('ids');
        $ids = explode(',', $ids);
        return $this->index()->whereKey($ids);
    }

    /**
     * @param $ids
     * @return bool
     * @throws \Exception
     */
    public function destroy($ids)
    {
        if ($ids === 'clean') {
            $this->model::query()->delete();
            return true;
        }

        $ids = explode(',', $ids);
        if ($this->model::whereKey($ids)->delete()) {
            return true;
        }
        return false;
    }
}
