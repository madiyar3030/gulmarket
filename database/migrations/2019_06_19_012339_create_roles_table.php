<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title')->unique();
            $table->integer('orders')->default(1);
            $table->integer('users')->default(1);
            $table->integer('chats')->default(1);
            $table->integer('categories')->default(1);
            $table->integer('items')->default(1);
            $table->integer('general')->default(1);
            $table->integer('admin')->default(0);
            $table->integer('roles')->default(1);
            $table->integer('lists')->default(1);
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
        Schema::dropIfExists('roles');
    }
}
