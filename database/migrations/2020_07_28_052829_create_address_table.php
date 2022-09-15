<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->increments('id');
            $table->float('latitude');
            $table->float('longitude');
            $table->string('street');
            $table->string('internal_number');
            $table->string('external_number');
            $table->string('neighborhood');
            $table->integer('postal_code')->unsigned();
            $table->integer('municipality_id')->unsigned();
            $table->integer('fed_entity_id')->unsigned();
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
        Schema::dropIfExists('addresses');
    }
}
