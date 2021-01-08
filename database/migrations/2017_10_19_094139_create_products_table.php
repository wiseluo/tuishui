<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->comment('客户');
            $table->string('name')->comment('产品名称');
            $table->string('en_name')->comment('英文名称');
            $table->string('unit', 10)->nullable()->comment('单位');
            $table->string('hscode')->comment('HSCode');
            $table->string('standard')->comment('型号');
            $table->string('number')->comment('货号');
            $table->decimal('rate', 4, 2)->default(0.00)->comment('退税率');
            $table->tinyInteger('status')->default(0)->comment('审核状态 1:草稿,2:审批中,3:审批通过,4:审批拒绝');
            $table->char('approved_at', 10)->nullable();
            $table->text('opinion');
            $table->text('remark')->comment('备注');
            $table->string('picture')->default('');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_user_id')->nullable()->comment('操作人用户id');
            $table->string('created_user_name')->nullable()->comment('操作人用户名');
            $table->integer('updated_user_id')->nullable()->comment('操作人用户id');
            $table->string('updated_user_name')->nullable()->comment('操作人用户名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
