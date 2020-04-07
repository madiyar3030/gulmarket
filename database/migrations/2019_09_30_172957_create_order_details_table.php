<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('order_id')->unsigned()->index();
            $table->string('name');
            $table->string('surname')->nullable();
            $table->string('phone');
            $table->string('email')->nullable();
            $table->bigInteger('city_id')->unsigned()->index();
            $table->string('street')->nullable();
            $table->string('house')->nullable();
            $table->string('entrance')->nullable();
            $table->string('floor')->nullable();
            $table->string('building')->nullable();
            $table->string('flat_number')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();
        });
        Schema::table('order_details', function($table) {
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('order_id')->references('id')->on('orders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
