<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('description')->nullable();
            $table->string('type');
            $table->integer('default_role')->unsigned()->nullable();
            $table->integer('municipality_id')->unsigned()->nullable();
            $table->integer('fed_entity_id')->unsigned()->nullable();
            $table->integer('section_id')->unsigned()->nullable();
            $table->integer('fed_district_id')->unsigned()->nullable();
            $table->integer('loc_district_id')->unsigned()->nullable();
            $table->integer('area_id')->unsigned()->nullable();
            $table->integer('zone_id')->unsigned()->nullable();
            $table->integer('block_id')->unsigned()->nullable();
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
        Schema::dropIfExists('roles');
    }
}
