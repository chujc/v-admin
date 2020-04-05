<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminUsers extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function getConnection()
    {
        return config('admin.database.connection') ?: config('database.default');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableName = config('admin.database.users_table');

        Schema::create('admin_users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigIncrements('user_id')->nullable(false)->comment('用户ID');
            $table->string('username', 30)->nullable(false)->comment('用户账号');
            $table->string('nickname', 30)->nullable(false)->comment('用户昵称');
            $table->string('password', 200)->nullable()->default('')->comment('密码');
            $table->string('avatar', 200)->nullable(false)->default('')->comment('头像地址');
            $table->boolean('status')->nullable(false)->default(0)->comment('帐号状态（1正常 0停用）');
            $table->string('remark', 500)->nullable()->default(null)->comment('备注');
            $table->string('login_ip', 50)->nullable(false)->default('')->comment('最后登陆IP');
            $table->datetime('login_date')->nullable()->default(null)->comment('最后登陆时间');
            $table->unsignedBigInteger('created_by')->nullable()->default(0)->comment('创建者');
            $table->datetime('created_at')->nullable()->default(null)->comment('创建时间');
            $table->unsignedBigInteger('updated_by')->nullable()->default(0)->comment('更新者');
            $table->datetime('updated_at')->nullable()->default(null)->comment('更新时间');
            $table->datetime('deleted_at')->nullable()->default(null)->comment('删除时间');

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '管理员信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.users_table'));
    }
}
