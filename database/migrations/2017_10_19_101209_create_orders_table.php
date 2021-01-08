<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->comment('客户');
            $table->string('number')->nullable()->comment('订单号');
            $table->string('contract')->nullable()->comment('合同类型');
            $table->string('business')->nullable()->comment('业务类型');
            $table->string('sales_unit')->nullable()->comment('经营单位');
            $table->string('clearance_port')->nullable()->comment('报关口岸');
            $table->string('shipment_port')->nullable()->comment('起运港');
            $table->string('declare_mode')->nullable()->comment('报关方式');
            $table->string('currency')->nullable()->comment('币种');
            $table->string('package')->nullable()->comment('包装方式');
            $table->string('price_clause')->nullable()->comment('价格条款');
            $table->string('loading_mode')->nullable()->comment('装柜方式');
            $table->integer('package_number')->nullable()->comment('整体包装件数');
            $table->string('order_package')->comment('整体包装方式');
            $table->tinyInteger('status')->nullable()->comment('审核状态 1:草稿,2:审批中,3:审批通过,4:审批拒绝');
            $table->char('approved_at', 10)->nullable();
            $table->text('opinion');
            $table->char('shipping_at', 20)->nullable()->comment('预计出货日期');
            $table->string('clearance_mode')->nullable()->comment('报关形式');
            $table->string('is_pay_special')->nullable()->comment('支付特许权使用费确认');
            $table->tinyInteger('is_special')->default(1)->comment('特殊关系确认');
            $table->string('unloading_port')->nullable()->comment('抵运港');
            $table->char('customs_number', 18)->nullable()->comment('报关单号');
            $table->string('aim_country')->nullable()->comment('最终目的国');
            $table->string('trade_country')->nullable()->comment('贸易国');
            $table->string('broker_number', 50)->nullable()->comment('报关行代码(10位)');
            $table->string('broker_name')->nullable()->comment('报关行名称');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('created_user_id')->nullable()->comment('操作人用户id');
            $table->string('created_user_name')->nullable()->comment('操作人用户名');
            $table->integer('updated_user_id')->nullable()->comment('操作人用户id');
            $table->string('updated_user_name')->nullable()->comment('操作人用户名');
            $table->integer('trader_id')->nullable()->comment('贸易商');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
