<?php
/**
 * Class AdminDictDatum
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ChuJC\Admin\Models\AdminDictDatum
 *
 * @property int $dict_code 字典编码
 * @property int $dict_order 字典排序
 * @property string $dict_label 字典标签
 * @property string $dict_value 字典键值
 * @property string|null $dict_type 字典类型
 * @property int|null $is_default 是否默认（1是 0否）
 * @property int|null $status 状态（1正常 0停用）
 * @property string|null $remark 备注
 * @property int|null $created_by 创建者
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property int|null $updated_by 更新者
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property \Illuminate\Support\Carbon|null $deleted_at 删除时间
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum newQuery()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminDictDatum onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereDictCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereDictLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereDictOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereDictType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereDictValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminDictDatum whereUpdatedBy($value)
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminDictDatum withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminDictDatum withoutTrashed()
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminDictDatum extends Model
{
    use SoftDeletes;

    protected $primaryKey = 'dict_code';

    protected $fillable = [
        'dict_order',
        'dict_label',
        'dict_value',
        'dict_type',
        'is_default',
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

        $this->setTable(config('admin.database.dict_data_table'));

        parent::__construct($attributes);
    }
}
