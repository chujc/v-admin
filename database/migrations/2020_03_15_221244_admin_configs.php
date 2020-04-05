<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminConfigs extends Migration
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
        $tableName = config('admin.database.configs_table');

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->bigIncrements('config_id')->nullable(false)->comment('参数主键');
            $table->string('config_name', 100)->nullable(false)->default('')->comment('参数名称');
            $table->string('config_key', 100)->nullable(false)->default('')->comment('参数键名');
            $table->string('config_value', 500)->nullable(false)->default('')->comment('参数键值');
            $table->boolean('is_system')->nullable(false)->default(0)->comment('是否系统内置（1是 0否）');
            $table->string('remark', 500)->nullable()->default(null)->comment('备注');
            $table->datetime('created_at')->nullable()->default(null)->comment('创建时间');
            $table->unsignedBigInteger('created_by')->nullable()->default(0)->comment('创建者');
            $table->datetime('updated_at')->nullable()->default(null)->comment('更新时间');
            $table->unsignedBigInteger('updated_by')->nullable()->default(0)->comment('更新者');
            $table->datetime('deleted_at')->nullable()->default(null)->comment('删除时间');

        });

        $tableName = fullTableName($tableName);;
        DB::statement("alter table `{$tableName}` comment '参数配置表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.configs_table'));
    }
}
