<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmvGenreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amv_genre', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amv_id')->unsigned();
            $table->integer('genre_id')->unsigned();
            $table->foreign('amv_id')->references('id')->on('amvs')->onDelete('cascade');
            $table->foreign('genre_id')->references('id')->on('genres')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amv_genre');
    }
}
