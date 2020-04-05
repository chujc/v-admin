<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminLoginLogs extends Migration
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
        $tableName = config('admin.database.login_logs_table');

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigIncrements('id')->nullable(false)->comment('访问ID');
            $table->string('username', 50)->nullable()->default('')->comment('登录账号');
            $table->string('ip', 50)->nullable()->default('')->comment('登录IP地址');
            $table->string('location', 100)->nullable()->default('')->comment('地址');
            $table->string('browser', 50)->nullable()->default('')->comment('浏览器类型');
            $table->string('os', 50)->nullable()->default('')->comment('操作系统');
            $table->string('http_user_agent', 255)->nullable()->default(null)->comment('user_agent');
            $table->boolean('status')->nullable()->default(0)->comment('登录状态（1成功 0失败）');
            $table->datetime('login_time')->nullable()->default(null)->comment('登录时间');
            $table->string('error', 255)->nullable()->default('')->comment('登录错误信息');

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '系统访问记录'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.login_logs_table'));
    }
}
