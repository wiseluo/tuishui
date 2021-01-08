<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRemitteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('remittees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('remit_type')->nullable()->comment('收款方类型');
            $table->integer('remit_id')->nullable();
            $table->string('name')->nullable()->comment('开户名');
            $table->integer('number')->nullable()->comment('收款账号');
            $table->string('bank')->nullable()->comment('开户银行');
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('remittees');
    }
}
