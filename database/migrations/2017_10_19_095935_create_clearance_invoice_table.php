<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClearanceInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clearance_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('clearance_id');
            $table->integer('invoice_id');
            $table->decimal('price', 10, 2)->comment('单价(含税)');
            $table->integer('amount')->comment('发票数量');
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
        Schema::dropIfExists('clearance_invoice');
    }
}
