<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePersonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table->increments('id');
            $table->string('person_name');
            $table->string('father_lastname');
            $table->string('mother_lastname');
            $table->date('birth_date');
            $table->char('person_sex');
            $table->integer('elector_key')->unsigned();
            $table->string('person_cellphone');
            $table->string('person_phone');
            $table->string('person_email',191)->unique();
            $table->string('validity')->unsigned();
            $table->integer('educ_level')->unsigned();
            $table->smallInteger('is_studying')->default(0);
            $table->smallInteger('is_working')->default(0);
            $table->smallInteger('territory_volunteer')->default(0);
            $table->smallInteger('electoral_volunteer')->default(0);
            $table->string('pdf');
            $table->integer('promoter')->unsigned()->nullable();
            $table->integer('oficial_address_id')->unsigned()->nullable();
            $table->integer('real_address_id')->unsigned()->nullable();
            $table->integer('section_id')->unsigned()->nullable();
            $table->integer('municipality_id')->unsigned()->nullable();
            $table->integer('fed_entity_id')->unsigned()->nullable();
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
        Schema::dropIfExists('persons')->Cascade();
    }
}
