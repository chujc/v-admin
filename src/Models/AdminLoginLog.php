<?php
/**
 * Class AdminLoginLog
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * ChuJC\Admin\Models\AdminLoginLog
 *
 * @property int $id 访问ID
 * @property string|null $username 登录账号
 * @property string|null $ip 登录IP地址
 * @property string|null $location 地址
 * @property string|null $browser 浏览器类型
 * @property string|null $os 操作系统
 * @property string|null $http_user_agent user_agent
 * @property int|null $status 登录状态（1成功 0失败）
 * @property string|null $login_time 登录时间
 * @property string|null $error 登录错误信息
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereBrowser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereHttpUserAgent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereLoginTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminLoginLog whereUsername($value)
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminLoginLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'username',
        'ip',
        'location',
        'browser',
        'os',
        'http_user_agent',
        'status',
        'login_time',
        'error'
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

        $this->setTable(config('admin.database.login_logs_table'));

        parent::__construct($attributes);
    }

}
