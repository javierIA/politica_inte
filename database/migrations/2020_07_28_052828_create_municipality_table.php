<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMunicipalityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('municipalitys', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('municipality_key')->unsigned();
            $table->string('municipality_name');
            $table->string('map_pdf')->nullable();
            $table->integer('titular_person')->unsigned()->nullable();
            $table->integer('vocal_person')->unsigned()->nullable();
            $table->integer('representative')->unsigned()->nullable();
            $table->integer('alternate')->unsigned()->nullable();
            $table->integer('fed_entity_id')->unsigned();
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
        Schema::dropIfExists('municipalitys');
    }
}
