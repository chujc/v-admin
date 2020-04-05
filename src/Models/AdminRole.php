<?php
/**
 * Class AdminRole
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use ChuJC\Admin\Models\Traits\HasOperationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ChuJC\Admin\Models\AdminRole
 *
 * @property int $role_id 角色ID
 * @property string $role_name 角色名称
 * @property string $role_key 角色权限字符串
 * @property int $role_order 显示顺序
 * @property int $status 角色状态（0正常 1停用）
 * @property string|null $remark 备注
 * @property int|null $created_by 创建者
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property int|null $updated_by 更新者
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property \Illuminate\Support\Carbon|null $deleted_at 删除时间
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole newQuery()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminRole onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereRoleKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereRoleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereRoleOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminRole whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminRole withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminRole withoutTrashed()
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminRole extends Model
{
    use SoftDeletes, HasOperationTrait;

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_name',
        'role_key',
        'role_order',
        'status',
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

        $this->setTable(config('admin.database.roles_table'));

        parent::__construct($attributes);
    }

    /**
     * 角色菜单
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function menus()
    {
        $pivotTable = config('admin.database.role_menu_table');

        $relatedModel = config('admin.database.menus_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'role_id', 'menu_id');
    }
}
