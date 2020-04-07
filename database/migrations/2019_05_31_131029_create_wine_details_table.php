<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWineDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wine_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('item_id')->unsigned()->index();
            $table->bigInteger('country_id')->unsigned()->index();
            $table->bigInteger('class_id')->unsigned()->index();
            $table->bigInteger('manufacturer_id')->unsigned()->index();
            $table->integer('age')->default(0);
            $table->float('volume')->default(0);
            $table->timestamps();
        });
        Schema::table('wine_details', function($table) {
            $table->foreign('item_id')->references('id')->on('items');
            $table->foreign('country_id')->references('id')->on('wine_countries');
            $table->foreign('class_id')->references('id')->on('wine_classes');
            $table->foreign('manufacturer_id')->references('id')->on('wine_manufacturers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wine_details');
    }
}
