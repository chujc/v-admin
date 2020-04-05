<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminRoles extends Migration
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
        $tableName = config('admin.database.roles_table');

        Schema::create('admin_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigIncrements('role_id')->nullable(false)->comment('角色ID');
            $table->string('role_name', 30)->nullable(false)->comment('角色名称');
            $table->string('role_key', 100)->nullable(false)->comment('角色权限字符串');
            $table->boolean('status')->nullable(false)->default(1)->comment('角色状态（1正常 0停用）');
            $table->string('remark', 500)->nullable()->default(null)->comment('备注');
            $table->unsignedBigInteger('created_by')->nullable()->default(0)->comment('创建者');
            $table->datetime('created_at')->nullable()->default(null)->comment('创建时间');
            $table->unsignedBigInteger('updated_by')->nullable()->default(0)->comment('更新者');
            $table->datetime('updated_at')->nullable()->default(null)->comment('更新时间');
            $table->datetime('deleted_at')->nullable()->default(null)->comment('删除时间');

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '角色信息表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.roles_table'));
    }
}
