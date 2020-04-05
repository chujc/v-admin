<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminDictData extends Migration
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
        $tableName = config('admin.database.dict_data_table');

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigIncrements('dict_code')->nullable(false)->comment('字典编码');
            $table->string('dict_label', 100)->nullable(false)->default('')->comment('字典标签');
            $table->string('dict_value', 100)->nullable(false)->default('')->comment('字典键值');
            $table->string('dict_type', 100)->nullable(false)->default('')->comment('字典类型');
            $table->unsignedInteger('dict_order')->nullable(false)->default(0)->comment('字典排序');
            $table->boolean('is_default')->nullable(false)->default(0)->comment('是否默认（1是 0否）');
            $table->boolean('is_system')->nullable(false)->default(0)->comment('是否系统内置（1是 0否）');
            $table->boolean('status')->nullable(false)->default(1)->comment('状态（1正常 0停用）');
            $table->string('remark', 500)->nullable()->default('')->comment('备注');
            $table->unsignedBigInteger('created_by')->nullable()->default(0)->comment('创建者');
            $table->datetime('created_at')->nullable()->default(null)->comment('创建时间');
            $table->unsignedBigInteger('updated_by')->nullable()->default(0)->comment('更新者');
            $table->datetime('updated_at')->nullable()->default(null)->comment('更新时间');
            $table->datetime('deleted_at')->nullable()->default(null)->comment('删除时间');

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '字典数据表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.dict_data_table'));
    }
}
