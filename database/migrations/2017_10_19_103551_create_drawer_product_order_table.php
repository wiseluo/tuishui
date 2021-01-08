<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrawerProductOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drawer_product_order', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->nullable();
            $table->integer('drawer_product_id')->nullable();
            $table->string('origin_country')->nullable();
            $table->integer('number')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('single_price', 10, 0)->nullable();
            $table->integer('default_num')->nullable();
            $table->string('value')->nullable();
            $table->string('volume')->nullable();
            $table->double('net_weight', 0, 0)->nullable();
            $table->double('total_weight', 0, 0)->nullable();
            $table->string('legal_unit')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('drawer_product_order');
    }
}
