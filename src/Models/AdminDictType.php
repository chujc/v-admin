<?php
/**
 * Class AdminDictType
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use ChuJC\Admin\Models\Traits\HasOperationTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ChuJC\Admin\Models\AdminDictType
 *
 * @property int $dict_id 字典主键
 * @property string $dict_name 字典名称
 * @property string $dict_type 字典类型
 * @property int $status 状态（1正常 0停用）
 * @property string|null $remark 备注
 * @property int|null $created_by 创建者
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property int|null $updated_by 更新者
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property \Illuminate\Support\Carbon|null $deleted_at 删除时间
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType newQuery()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminDictType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereDictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereDictName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereDictType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictType whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminDictType withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminDictType withoutTrashed()
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminDictType extends Model
{
    use SoftDeletes, HasOperationTrait;

    protected $primaryKey = 'dict_id';

    protected $fillable = [
        'dict_name',
        'dict_type',
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

        $this->setTable(config('admin.database.dict_types_table'));

        parent::__construct($attributes);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function dictDatum()
    {
        return $this->hasMany(config('admin.database.dict_data_model', AdminDictDatum::class), 'dict_type', 'dict_type');
    }

}
