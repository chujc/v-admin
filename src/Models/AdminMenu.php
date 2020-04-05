<?php
/**
 * Class AdminMenu
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use ChuJC\Admin\Facades\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

/**
 * ChuJC\Admin\Models\AdminMenu
 *
 * @property int $menu_id 菜单ID
 * @property string $menu_name 菜单名称
 * @property int|null $parent_id 父菜单ID
 * @property int|null $order 显示顺序
 * @property string|null $path 路由地址
 * @property string|null $component 组件路径
 * @property int|null $is_link 是否为外链（1是 0否）
 * @property int|null $menu_type 菜单类型（1菜单 2按钮）
 * @property int|null $is_visible 菜单状态（1显示 0隐藏）
 * @property string|null $permission 权限标识
 * @property string|null $icon 菜单图标
 * @property string|null $remark 备注
 * @property int|null $created_by 创建者
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property int|null $updated_by 更新者
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property \Illuminate\Support\Carbon|null $deleted_at 删除时间
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu newQuery()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminMenu onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereComponent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereIsLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereIsVisible($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereMenuId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereMenuName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereMenuType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminMenu whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminMenu withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminMenu withoutTrashed()
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminMenu extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'menu_name',
        'parent_id',
        'order',
        'path',
        'component',
        'is_link',
        'menu_type',
        'is_visible',
        'permission',
        'icon',
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

        $this->setTable(config('admin.database.menus_table'));

        parent::__construct($attributes);
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booting()
    {
        static::creating(function ($model) {
            $model->created_by = Admin::user() ? Admin::user()->getKey() : 0;
            Cache::forget(config('admin.cache.menus.key', 'admin.menus.permission'));
        });
        static::updating(function ($model) {
            $model->updated_by = Admin::user() ? Admin::user()->getKey() : 0;
            Cache::forget(config('admin.cache.menus.key', 'admin.menus.permission'));
        });
    }
}
