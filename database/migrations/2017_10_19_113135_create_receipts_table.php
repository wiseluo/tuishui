<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receipts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('currency')->nullable()->comment('币种');
            $table->string('picture')->nullable();
            $table->string('bank')->nullable()->comment('汇款银行');
            $table->string('identifier')->nullable()->comment('收汇单号');
            $table->string('number', 50)->nullable()->comment('汇款账号');
            $table->string('remitter')->nullable()->comment('汇款人');
            $table->string('received_at')->nullable()->comment('收汇日期');
            $table->decimal('rate', 10, 4)->default(0.0000);
            $table->decimal('money', 10, 2)->default(0.00)->comment('金额');
            $table->integer('customer_id')->nullable();
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
        Schema::dropIfExists('receipts');
    }
}
