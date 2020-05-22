<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('city_id')->unsigned()->index();;
            $table->string('address');
            $table->string('phone');
            $table->string('workHour');
            $table->decimal('lng', 10, 7);
            $table->decimal('lat', 10, 7);
            $table->timestamps();
        });
        Schema::table('contacts', function ($table) {
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
        Schema::dropIfExists('contacts');
    }
}
