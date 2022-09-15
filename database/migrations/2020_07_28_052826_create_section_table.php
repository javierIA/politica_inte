<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('section_key')->unsigned();
            $table->char('section_type');
            $table->string('pdf')->nullable();;
            $table->integer('loc_district_id')->unsigned();
            $table->integer('fed_district_id')->unsigned();
            $table->integer('fed_entity_id')->unsigned();
            $table->integer('gen_representation_id')->unsigned();
            $table->integer('area_id')->unsigned();
            $table->integer('zone_id')->unsigned();
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
        Schema::dropIfExists('sections');
    }
}
