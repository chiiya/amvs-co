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
            $table->text('animes');
            $table->string('music');
            $table->text('description')->nullable();
            $table->string('poster')->nullable();
            $table->string('bg')->nullable();
            $table->string('video')->nullable();
            $table->string('videoHost')->nullable();
            $table->string('videoCode')->nullable();
            $table->string('url');
            $table->boolean('published');
            $table->string('download')->nullable();
            $table->string('driveId')->nullable();
            $table->timestamps();
            $table->integer('user_id')->unsigned();
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
