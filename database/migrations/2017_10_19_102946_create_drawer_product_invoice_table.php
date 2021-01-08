<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrawerProductInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawer_product_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id');
            $table->integer('drawer_product_id');
            $table->integer('clearance_id')->nullable();
            $table->decimal('price', 50, 10)->default(0.0000000000)->comment('单价(含税)');
            $table->integer('rate')->default(0);
            $table->integer('amount')->default(0)->comment('发票数量');
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
        Schema::dropIfExists('drawer_product_invoice');
    }
}
