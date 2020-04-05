<?php
/**
 * Class AdminUser
 * @package ChuJC\Admin\Models
 * @date 2020-02-29
 */

namespace ChuJC\Admin\Models;

use ChuJC\Admin\Models\Traits\HasOperationTrait;
use ChuJC\Admin\Models\Traits\HasPermissionsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Storage;

/**
 * ChuJC\Admin\Models\AdminUser
 *
 * @property int $user_id 用户ID
 * @property string $username 用户账号
 * @property string $nickname 用户昵称
 * @property string|null $password 密码
 * @property string $avatar 头像地址
 * @property int $status 帐号状态（0正常 1停用）
 * @property string|null $remark 备注
 * @property string $login_ip 最后登陆IP
 * @property string|null $login_date 最后登陆时间
 * @property int|null $created_by 创建者
 * @property \Illuminate\Support\Carbon|null $created_at 创建时间
 * @property int|null $updated_by 更新者
 * @property \Illuminate\Support\Carbon|null $updated_at 更新时间
 * @property \Illuminate\Support\Carbon|null $deleted_at 删除时间
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser newQuery()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminUser onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereLoginDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereLoginIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereNickname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\ChuJC\Admin\Models\AdminUser whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminUser withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\ChuJC\Admin\Models\AdminUser withoutTrashed()
 * @mixin \Eloquent
 * @author john_chu
 */
class AdminUser extends Model implements AuthenticatableContract, JWTSubject
{
    use SoftDeletes, Authenticatable, HasPermissionsTrait, HasOperationTrait;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'username',
        'nickname',
        'password',
        'avatar',
        'status',
        'remark',
        'login_ip',
        'login_date',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
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

        $this->setTable(config('admin.database.users_table'));

        parent::__construct($attributes);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 角色关联模型
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        $pivotTable = config('admin.database.user_role_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id');
    }

    /**
     * 有效的角色
     */
    public function effectiveRoles()
    {
        return $this->roles()->where('status', 1);
    }


    /**
     * Get avatar attribute.
     *
     * @param string $avatar
     *
     * @return string
     */
    public function getAvatarAttribute($avatar)
    {
        if (app('url')->isValidUrl($avatar)) {
            return $avatar;
        }

        $disk = config('admin.upload.disk', 'admin');

        if ($avatar && array_key_exists($disk, config('filesystems.disks'))) {
            return Storage::disk($disk)->url($avatar);
        }
        return $avatar;
    }

}
