<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilingInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filing_invoice', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned()->index();
            $table->integer('filing_id')->unsigned();
            $table->softDeletes();
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
        Schema::dropIfExists('filing_invoice');
    }
}
