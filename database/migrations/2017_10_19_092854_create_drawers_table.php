<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrawersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->comment('客户');
            $table->string('company')->comment('开票人公司名称');
            $table->string('telephone')->comment('开票人联系方式');
            $table->string('phone')->comment('座机');
            $table->string('tax_id')->comment('纳税人识别号');
            $table->string('licence')->comment('营业执照注册号');
            $table->string('address')->comment('开票人地址');
            $table->string('source')->comment('境内货源地');
            $table->string('pic_register')->comment('税务登记副本');
            $table->string('pic_verification')->comment('一般纳税人认定书');
            $table->string('pic_production')->comment('产品');
            $table->string('pic_brand')->comment('品牌');
            $table->string('pic_other')->comment('其他图片');
            $table->tinyInteger('status')->comment('状态');
            $table->char('approved_at', 10)->nullable();
            $table->text('opinion');
            $table->string('tax_at', 20)->comment('一般纳税人认定时间');
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
        Schema::dropIfExists('drawers');
    }
}
