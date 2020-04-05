<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AdminDictTypes extends Migration
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
        $tableName = config('admin.database.dict_types_table');

        Schema::create($tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
            // CONTENT
            $table->bigIncrements('dict_id')->nullable(false)->comment('字典主键');
            $table->string('dict_name', 100)->nullable(false)->default('')->comment('字典名称');
            $table->string('dict_type', 100)->nullable(false)->default('')->comment('字典类型');
            $table->boolean('is_system')->nullable(false)->default(0)->comment('是否系统内置（1是 0否）');
            $table->boolean('status')->nullable(false)->default(1)->comment('状态（1正常 0停用）');
            $table->string('remark', 500)->nullable(false)->default('')->comment('备注');
            $table->unsignedBigInteger('created_by')->nullable()->default(0)->comment('创建者');
            $table->datetime('created_at')->nullable()->default(null)->comment('创建时间');
            $table->unsignedBigInteger('updated_by')->nullable()->default(0)->comment('更新者');
            $table->datetime('updated_at')->nullable()->default(null)->comment('更新时间');
            $table->datetime('deleted_at')->nullable()->default(null)->comment('删除时间');
            $table->unique('dict_type', 'dict_type');

        });

        $tableName = fullTableName($tableName);
        DB::statement("alter table `{$tableName}` comment '字典类型表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('admin.database.dict_types_table'));
    }
}
