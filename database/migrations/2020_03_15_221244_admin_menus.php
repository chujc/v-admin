<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminMenus extends Migration
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
        $tableName = config('admin.database.menus_table');

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigIncrements('menu_id')->nullable(false)->comment('菜单ID');
            $table->string('menu_name', 50)->nullable(false)->comment('菜单名称');
            $table->bigInteger('parent_id')->nullable()->default(0)->comment('父菜单ID');
            $table->integer('order')->nullable()->default(0)->comment('显示顺序');
            $table->string('path', 200)->nullable()->default('')->comment('路由地址');
            $table->string('component', 255)->nullable()->default(null)->comment('组件路径');
            $table->boolean('is_link')->nullable()->default(1)->comment('是否为外链（1是 0否）');
            $table->boolean('menu_type')->nullable()->default(1)->comment('菜单类型（1菜单 2按钮）');
            $table->boolean('is_visible')->nullable()->default(0)->comment('菜单状态（1显示 0隐藏）');
            $table->string('permission', 100)->nullable()->default(null)->comment('权限标识');
            $table->string('icon', 100)->nullable()->default('#')->comment('菜单图标');
            $table->string('remark', 500)->nullable()->default('')->comment('备注');
            $table->unsignedBigInteger('created_by')->nullable()->default(0)->comment('创建者');
            $table->datetime('created_at')->nullable()->default(null)->comment('创建时间');
            $table->unsignedBigInteger('updated_by')->nullable()->default(0)->comment('更新者');
            $table->datetime('updated_at')->nullable()->default(null)->comment('更新时间');
            $table->datetime('deleted_at')->nullable()->default(null)->comment('删除时间');

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '菜单权限表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.menus_table'));
    }
}
