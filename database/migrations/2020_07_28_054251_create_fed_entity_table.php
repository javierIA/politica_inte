<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFedEntityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fed_entitys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_key')->unsigned();
            $table->string('entity_name');
            $table->string('map_pdf')->nullable();
            $table->integer('titular_person')->unsigned()->nullable();
            $table->integer('vocal_person')->unsigned()->nullable();
            $table->integer('representative')->unsigned()->nullable();
            $table->integer('alternate')->unsigned()->nullable();
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
        Schema::dropIfExists('fed_entitys');
    }
}
