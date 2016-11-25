<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amv_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('amv_id')->references('id')->on('amvs');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unique(['amv_id', 'user_id'], 'unique_like');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
    }
}
