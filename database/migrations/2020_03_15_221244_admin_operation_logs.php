<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminOperationLogs extends Migration
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
        $tableName = config('admin.database.operation_logs_table');

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigIncrements('id')->nullable(false)->comment('日志主键');
            $table->string('oper_name', 50)->nullable()->default('')->comment('操作人员');
            $table->string('method', 10)->nullable()->default('')->comment('请求方式');
            $table->string('url', 255)->nullable()->default('')->comment('请求URL');
            $table->string('ip', 50)->nullable()->default('')->comment('主机地址');
            $table->string('location', 100)->nullable()->default('')->comment('地址');
            $table->text('params')->nullable()->comment('请求参数');
            $table->char('status', 10)->nullable()->default(0)->comment('状态码');
            $table->longText('result')->nullable()->comment('返回参数');
            $table->string('message', 255)->nullable()->default('')->comment('消息体');
            $table->string('os', 50)->nullable()->default('')->comment('操作系统');
            $table->string('browser', 50)->nullable()->default('')->comment('浏览器类型');
            $table->string('http_user_agent', 255)->nullable()->default(null)->comment('user_agent');
            $table->datetime('created_at')->nullable()->default(null)->comment('请求时间');
            $table->datetime('updated_at')->nullable()->default(null)->comment('响应时间');

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '操作日志记录'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.operation_logs_table'));
    }
}
