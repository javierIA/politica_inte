<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBoxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('box', function (Blueprint $table) {
            $table->increments('id');
            $table->string('box_index');
            $table->integer('box_type_id')->unsigned()->nullable();
            $table->integer('titular_person1')->unsigned()->nullable();
            $table->integer('titular_person2')->unsigned()->nullable();
            $table->integer('vocal_person')->unsigned()->nullable();
            $table->integer('owner')->unsigned()->nullable();
            $table->integer('address_id')->unsigned()->nullable();
            $table->integer('section_id')->unsigned();
            $table->integer('president')->unsigned()->nullable();
            $table->integer('secretary')->unsigned()->nullable();
            $table->integer('teller1')->unsigned()->nullable();
            $table->integer('teller2')->unsigned()->nullable();
            $table->integer('substitute1')->unsigned()->nullable();
            $table->integer('substitute2')->unsigned()->nullable();
            $table->integer('substitute3')->unsigned()->nullable();
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
        Schema::dropIfExists('box');
    }
}
