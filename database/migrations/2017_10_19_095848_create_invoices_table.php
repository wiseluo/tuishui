<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clearance_id')->nullable();
            $table->integer('drawer_id')->nullable();
            $table->integer('order_id')->nullable();
            $table->integer('filing_id')->nullable();
            $table->string('number')->nullable()->comment('发票号');
            $table->date('billed_at')->nullable()->comment('开票时间');
            $table->date('received_at')->nullable()->comment('收票时间');
            $table->decimal('sum', 10, 2)->nullable();
            $table->tinyInteger('status')->nullable()->comment('审核状态 1:草稿,2:审批中,3:待申报,4:审批拒绝,5已申报');
            $table->char('approved_at', 10)->nullable();
            $table->text('opinion');
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
        Schema::dropIfExists('invoices');
    }
}
