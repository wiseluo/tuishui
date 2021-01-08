<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number', 20)->default('0')->comment('客户编号');
            $table->string('name', 25)->default('')->comment('客户名称');
            $table->string('linkman', 25)->default('')->comment('联系人');
            $table->string('address')->default('')->comment('地址');
            $table->string('telephone')->default('')->comment('联系电话');
            $table->tinyInteger('service_rate')->default(0)->comment('服务费率(%)');
            $table->string('receiver')->default('')->comment('收款人');
            $table->string('deposit_bank')->default('')->comment('开户银行');
            $table->string('bank_account')->default('')->comment('银行账号');
            $table->integer('user_id')->default(0)->comment('业务员id');
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
        Schema::dropIfExists('customers');
    }
}
