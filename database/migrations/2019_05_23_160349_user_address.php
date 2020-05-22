<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UserAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->unsigned()->index();
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
        Schema::table('user_addresses', function($table) {
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}
