<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClearancesCopyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clearances_copy', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->timestamp('deleted_at')->default('');
            $table->timestamps();
            $table->string('generator')->default('')->comment('生成器');
            $table->string('prerecord')->default('')->comment('报关预录单');
            $table->string('declare')->default('')->comment('报关单');
            $table->string('release')->default('')->comment('放行书');
            $table->string('lading')->default('')->comment('提单');
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
        Schema::dropIfExists('clearances_copy');
    }
}
