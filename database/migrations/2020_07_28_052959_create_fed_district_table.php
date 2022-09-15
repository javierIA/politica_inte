<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFedDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fed_districts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('district_number')->unsigned();
            $table->string('map_pdf');
            $table->integer('municipality_id')->unsigned();
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
        Schema::dropIfExists('fed_districts');
    }
}
