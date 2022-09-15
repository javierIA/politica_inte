<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocDistrictTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loc_districts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('district_number')->unsigned();
            $table->string('map_pdf');
            $table->integer('municipality_id')->unsigned();
            $table->integer('titular_person')->unsigned()->nullable();
            $table->integer('vocal_person')->unsigned()->nullable();
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
        Schema::dropIfExists('loc_districts');
    }
}
