<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pays', function (Blueprint $table) {
            $table->increments('id');
            $table->date('applied_at')->nullable()->comment('申请日期');
            $table->string('order_id')->nullable();
            $table->string('type')->nullable()->comment('款项类型');
            $table->string('content')->nullable()->comment('款项内容');
            $table->integer('remittee_id')->nullable()->comment('收款单位');
            $table->text('opinion');
            $table->string('money')->nullable()->comment('金额');
            $table->tinyInteger('status')->nullable()->comment('状态');
            $table->string('picture')->nullable()->comment('付款附件');
            $table->date('pay_at')->nullable()->comment('付款日期');
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
        Schema::dropIfExists('pays');
    }
}
