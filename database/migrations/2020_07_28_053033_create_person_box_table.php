<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonBoxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_box', function (Blueprint $table) {
            $table->increments('id');
            $table->string('person_box_name');
            $table->integer('person_id')->unsigned()->nullable();
            $table->integer('box_id')->unsigned();
            $table->integer('section_id')->unsigned();
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
        Schema::dropIfExists('person_box');
    }
}
