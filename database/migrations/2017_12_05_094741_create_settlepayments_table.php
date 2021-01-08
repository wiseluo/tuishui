<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettlepaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settlepayments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('settlement_id')->unsigned()->comment('结算');
            $table->integer('drawer_id')->unsigned()->comment('开票工厂id');
            $table->string('company')->nullable()->comment('开票工厂');
            $table->decimal('invoice_value', 10, 2)->nullable()->comment('开票金额');
            $table->decimal('invoice_sum', 10, 2)->nullable()->comment('已开票金额');
            $table->decimal('paid_deposit', 10, 2)->nullable()->comment('已付定金');
            $table->decimal('proposed_paid', 10, 2)->nullable()->comment('拟付货款金额');
            $table->decimal('proposed_deposit', 10, 2)->nullable()->comment('拟付退税款金额');
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
        Schema::dropIfExists('settlepayments');
    }
}
