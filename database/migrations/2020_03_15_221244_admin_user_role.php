<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminUserRole extends Migration
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
        $tableName = config('admin.database.user_role_table');

        Schema::create('admin_user_role', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigInteger('user_id')->nullable(false)->comment('用户ID');
            $table->bigInteger('role_id')->nullable(false)->comment('角色ID');
            $table->primary(['user_id', 'role_id']);

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '用户和角色关联表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.user_role_table'));
    }
}
