<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amvs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('genre');
            $table->text('animes');
            $table->string('music');
            $table->text('description');
            $table->string('poster');
            $table->string('bg');
            $table->string('video');
            $table->integer('user_id')->unsigned();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amvs');
    }
}
