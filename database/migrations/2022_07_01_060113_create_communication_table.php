<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommunicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('communications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type');
            $table->string('lada')->nullable();
            $table->string('info')->nullable();
            $table->boolean('is_propietary')->default(false);
            $table->boolean('is_smartphone')->default(false)->nullable();
            $table->boolean('is_exclusive')->default(false);
            $table->integer('phone_code_id')->unsigned();
            $table->integer('person_id')->unsigned();
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
        Schema::dropIfExists('communications');
    }
}
