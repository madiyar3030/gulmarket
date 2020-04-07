<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubCatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_cats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('cat_id')->unsigned()->index();
            $table->boolean('hidden')->default(0);
            $table->string('title');
            $table->timestamps();
        });
        Schema::table('sub_cats', function($table) {
            $table->foreign('cat_id')->references('id')->on('cats');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_cats');
    }
}
