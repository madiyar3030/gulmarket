<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('phone')->unique()->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('thumb')->nullable();
            $table->date('birth_date')->nullable();
            $table->integer('bonus')->default(0);
            $table->boolean('blocked')->default(0);
//            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('device_token', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
