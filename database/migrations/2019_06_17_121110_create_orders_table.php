<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index()->nullable();
            $table->integer('total');
            $table->integer('bonusUser')->default(0);
            $table->integer('bonusPrice')->default(0);
            $table->enum('payType', ['cash', 'card']);
            $table->bigInteger('shipping_id')->unsigned()->index();
            $table->date('orderDate');
            $table->enum('status', ['declined', 'waiting', 'accepted']);
            $table->timestamps();
        });
        Schema::table('orders', function ($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('shipping_id')->references('id')->on('shipping');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
