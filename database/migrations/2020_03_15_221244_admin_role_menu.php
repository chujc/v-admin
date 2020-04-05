<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminRoleMenu extends Migration
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
        $tableName = config('admin.database.role_menu_table');

        Schema::create('admin_role_menu', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigInteger('role_id')->nullable(false)->comment('角色ID');
            $table->bigInteger('menu_id')->nullable(false)->comment('菜单ID');
            $table->primary(['role_id', 'menu_id']);

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '角色和菜单关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.role_menu_table'));
    }
}
