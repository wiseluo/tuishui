<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->increments('id');
            $table->date('applied_at')->nullable()->comment('申请日期');
            $table->integer('order_id')->nullable()->comment('订单号');
            $table->string('type')->nullable()->comment('类型');
            $table->string('name')->nullable()->comment('开票名称');
            $table->date('billed_at')->nullable()->comment('开票时间');
            $table->char('number')->nullable()->comment('发票号码');
            $table->string('money')->nullable()->comment('金额');
            $table->tinyInteger('status')->nullable();
            $table->string('picture')->nullable();
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
        Schema::dropIfExists('transports');
    }
}
