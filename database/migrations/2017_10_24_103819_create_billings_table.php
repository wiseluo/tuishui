<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('data_id')->unique()->comment('经营单位id');
            $table->string('name')->comment('开票名称');
            $table->string('identification_num')->comment('纳税人识别号');
            $table->string('corporate_repre')->nullable()->comment('纳税人识别号');
            $table->string('address')->comment('地址');
            $table->string('telephone')->comment('电话');
            $table->string('bankname')->comment('开户行');
            $table->string('bankaccount')->comment('账号');
            $table->string('invoice_receipt_addr')->comment('发票收件地址');
            $table->string('invoice_recipient')->comment('发票收件人');
            $table->string('recipient_call')->comment('收件人电话');
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
        Schema::dropIfExists('billings');
    }
}
