<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlements', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned()->comment('订单id');
            $table->integer('user_id')->unsigned()->comment('结算人员id');
            $table->decimal('total_value', 10, 2)->nullable()->comment('订单金额(报关金额)');
            $table->decimal('invoice_sum', 10, 2)->nullable()->comment('已收票金额');
            $table->decimal('paid_deposit', 10, 2)->nullable()->comment('已付定金金额');
            $table->decimal('service_rate', 10, 2)->nullable()->comment('服务费率(%)');
            $table->decimal('retreat_amount', 10, 2)->nullable()->comment('应退税款');
            $table->decimal('profit_amount', 10, 2)->nullable()->comment('利润');
            $table->decimal('refundable_tax', 10, 2)->nullable()->comment('应付税款');
            $table->data('settle_at')->nullable()->comment('结算日期');
            $table->data('approved_at')->nullable()->comment('审批时间');
            $table->string('opinion')->nullable()->comment('审核意见');
            $table->tinyInteger('status')->default(1)->comment('1:待结算,2:审批中,3:已结算,4:审批拒绝');
            $table->timestamps();
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
        Schema::dropIfExists('settlements');
    }
}
