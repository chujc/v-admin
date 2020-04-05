<?php
/**
 * Class AdminOperationLog
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * ChuJC\Admin\Models\AdminOperationLog
 *
 * @property int $id 日志主键
 * @property string|null $oper_name 操作人员
 * @property string|null $method 请求方式
 * @property string|null $url 请求URL
 * @property string|null $ip 主机地址
 * @property string|null $location 地址
 * @property string|null $params 请求参数
 * @property string|null $status 状态码
 * @property string|null $result 返回参数
 * @property string|null $message 消息体
 * @property string|null $os 操作系统
 * @property string|null $browser 浏览器类型
 * @property string|null $http_user_agent user_agent
 * @property \Illuminate\Support\Carbon|null $created_at 请求时间
 * @property \Illuminate\Support\Carbon|null $updated_at 响应时间
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereHttpUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereOperName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminOperationLog whereUrl($value)
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminOperationLog extends Model
{
    protected $table = 'admin_operation_logs';

    protected $fillable = [
        'oper_name',
        'method',
        'url',
        'ip',
        'location',
        'params',
        'status',
        'result',
        'message',
        'os',
        'browser',
        'http_user_agent'
    ];

    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $connection = config('admin.database.connection') ?: config('database.default');

        $this->setConnection($connection);

        $this->setTable(config('admin.database.operation_logs_table'));

        parent::__construct($attributes);
    }
}
