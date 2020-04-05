<?php
/**
 * Class AdminConfig
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use ChuJC\Admin\Models\Traits\HasOperationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ChuJC\Admin\Models\AdminConfig
 *
 * @property int $config_id 参数主键
 * @property string $config_name 参数名称
 * @property string $config_key 参数键名
 * @property string $config_value 参数键值
 * @property int $is_system 系统内置（1是 0否）
 * @property string|null $remark 备注
 * @property int|null $created_by 创建者
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property int|null $updated_by 更新者
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property \Illuminate\Support\Carbon|null $deleted_at 删除时间
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig newQuery()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminConfig onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereConfigKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereConfigName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereIsSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereConfigValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminConfig whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminConfig withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminConfig withoutTrashed()
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminConfig extends Model
{
    use SoftDeletes, HasOperationTrait;

    protected $primaryKey = 'config_id';

    protected $fillable = [
        'config_name',
        'config_key',
        'config_value',
        'is_system',
        'remark',
        'created_by',
        'updated_by'
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

        $this->setTable(config('admin.database.configs_table'));

        parent::__construct($attributes);
    }
}
