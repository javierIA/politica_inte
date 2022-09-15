<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGenRepresentationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gen_representation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gen_representation_key');
            $table->string('map_pdf')->nullable();
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
        Schema::dropIfExists('gen_representation');
    }
}
