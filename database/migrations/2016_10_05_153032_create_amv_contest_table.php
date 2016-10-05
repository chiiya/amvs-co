<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmvContestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amv_contest', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('amv_id')->unsigned();
            $table->integer('contest_id')->unsigned();
            $table->string('award');
            $table->foreign('amv_id')->references('id')->on('amvs')->onDelete('cascade');
            $table->foreign('contest_id')->references('id')->on('contests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amv_contest');
    }
}
