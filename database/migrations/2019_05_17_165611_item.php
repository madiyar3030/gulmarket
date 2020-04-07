<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Item extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->nullable();
            $table->bigInteger('cat_id')->unsigned()->index();
            $table->bigInteger('sub_cat_id')->unsigned()->index()->nullable();
            $table->bigInteger('city_id')->unsigned()->index();
            $table->integer('price')->default(0);
            $table->integer('count')->default(1);
            $table->boolean('isNew')->default(0);
            $table->boolean('isDiscount')->default(0);
            $table->integer('height')->default(0);
            $table->integer('diameter')->default(0);
            $table->string('type')->default('default');
            $table->text('description')->nullable();
            $table->integer('bonusPercentage')->default(0);
            $table->timestamps();
        });
        Schema::table('items', function($table) {
            $table->foreign('cat_id')->references('id')->on('cats');
            $table->foreign('sub_cat_id')->references('id')->on('sub_cats');
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
        Schema::dropIfExists('items');
    }
}
