<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('persons', function ($table) {
            $table->foreign('promoter')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('oficial_address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('real_address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
            $table->foreign('municipality_id')->references('id')->on('municipalitys')->onDelete('set null');
            $table->foreign('fed_entity_id')->references('id')->on('fed_entitys')->onDelete('set null');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->foreign('loc_district_id')->references('id')->on('loc_districts')->onDelete('set null');
            $table->foreign('fed_district_id')->references('id')->on('fed_districts')->onDelete('set null');
            $table->foreign('fed_entity_id')->references('id')->on('fed_entitys')->onDelete('set null');
            $table->foreign('gen_representation_id')->references('id')->on('gen_representation')->onDelete('set null');
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('set null');
            $table->foreign('municipality_id')->references('id')->on('municipalitys')->onDelete('set null');
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('municipalitys', function (Blueprint $table) {
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('representative')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('alternate')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('fed_entity_id')->references('id')->on('fed_entitys')->onDelete('set null');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->foreign('municipality_id')->references('id')->on('municipalitys')->onDelete('cascade');
            $table->foreign('fed_entity_id')->references('id')->on('fed_entitys')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        Schema::table('loc_districts', function (Blueprint $table) {
            $table->foreign('municipality_id')->references('id')->on('municipalitys')->onDelete('cascade');
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('loc_district_id')->references('id')->on('loc_districts')->onDelete('set null');
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        Schema::table('box', function (Blueprint $table) {
            $table->foreign('box_type_id')->references('id')->on('box_types')->onDelete('set null');
            $table->foreign('titular_person1')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('titular_person2')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('owner')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
            $table->foreign('president')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('secretary')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('teller1')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('teller2')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('substitute1')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('substitute2')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('substitute3')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('fed_districts', function (Blueprint $table) {
            $table->foreign('municipality_id')->references('id')->on('municipalitys')->onDelete('cascade');
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('representative')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('alternate')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('group_person', function (Blueprint $table) {
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('person_box', function (Blueprint $table) {
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
            $table->foreign('box_id')->references('id')->on('box')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        Schema::table('political_function_person', function (Blueprint $table) {
            $table->foreign('political_function_id')->references('id')->on('political_functions')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('role_system_function', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('system_function_id')->references('id')->on('system_functions')->onDelete('cascade');
        });

        Schema::table('social_network_person', function (Blueprint $table) {
            $table->foreign('social_network_id')->references('id')->on('social_networks')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('user_role', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('promotor')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('validation_person', function (Blueprint $table) {
            $table->foreign('validation_id')->references('id')->on('validations')->onDelete('cascade');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('zones', function (Blueprint $table) {
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('gen_representation', function (Blueprint $table) {
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('cascade');
        });

        Schema::table('fed_entitys', function (Blueprint $table) {
            $table->foreign('titular_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('vocal_person')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('representative')->references('id')->on('persons')->onDelete('set null');
            $table->foreign('alternate')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('communications', function (Blueprint $table) {
            $table->foreign('phone_code_id')->references('id')->on('phone_codes')->onDelete('set null');
            $table->foreign('person_id')->references('id')->on('persons')->onDelete('set null');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->foreign('id_user_to')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_user_from')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::table('postal_codes', function (Blueprint $table) {
            $table->foreign('fed_entity_id')->references('id')->on('fed_entitys')->onDelete('cascade');
            $table->foreign('municipality_id')->references('id')->on('municipalitys')->onDelete('cascade');
            $table->foreign('colony_id')->references('id')->on('colonies')->onDelete('cascade');
        });

        Schema::table('role_group', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->foreign('default_role')->references('id')->on('roles')->onDelete('set null');
        });

        Schema::table('roles', function (Blueprint $table) {
            //$table->foreign('default_role')->references('id')->on('roles')->onDelete('set null');
            $table->foreign('municipality_id')->references('id')->on('municipalitys')->onDelete('set null');
            $table->foreign('fed_entity_id')->references('id')->on('fed_entitys')->onDelete('set null');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('set null');
            $table->foreign('fed_district_id')->references('id')->on('fed_districts')->onDelete('set null');
            $table->foreign('loc_district_id')->references('id')->on('loc_districts')->onDelete('set null');
            $table->foreign('area_id')->unsigned()->references('id')->on('areas')->onDelete('set null');
            $table->foreign('zone_id')->unsigned()->references('id')->on('zones')->onDelete('set null');
            $table->foreign('block_id')->unsigned()->references('id')->on('blocks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function ($table) {
            $table->dropColumn('municipality_id');
            $table->dropColumn('fed_entity_id');
            $table->dropColumn('section_id');
            $table->dropColumn('fed_district_id');
            $table->dropColumn('loc_district_id');
            $table->dropColumn('area_id');
            $table->dropColumn('zone_id');
            $table->dropColumn('block_id');
        });

        Schema::table('persons', function($table) {
            $table->dropColumn('promoter');
            $table->dropColumn('oficial_address_id');
            $table->dropColumn('real_address_id');
            $table->dropColumn('section_id');
            $table->dropColumn('municipality_id');
            $table->dropColumn('fed_entity_id');
        });

        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn('loc_district_id');
            $table->dropColumn('fed_district_id');
            $table->dropColumn('fed_entity_id');
            $table->dropColumn('gen_representation_id');
            $table->dropColumn('area_id');
            $table->dropColumn('zone_id');
            $table->dropColumn('municipality_id');
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
        });

        Schema::table('municipalitys', function (Blueprint $table) {
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
            $table->dropColumn('representative');
            $table->dropColumn('alternate');
            $table->dropColumn('fed_entity_id');
        });

        Schema::table('addresses', function (Blueprint $table) {
            $table->dropColumn('municipality_id');
            $table->dropColumn('fed_entity_id');
            $table->dropColumn('section_id');
        });

        Schema::table('loc_districts', function (Blueprint $table) {
            $table->dropColumn('municipality_id');
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
            $table->dropColumn('loc_district_id');
        });

        Schema::table('blocks', function (Blueprint $table) {
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
            $table->dropColumn('section_id');
        });

        Schema::table('box', function (Blueprint $table) {
            $table->dropColumn('box_type_id');
            $table->dropColumn('titular_person1');
            $table->dropColumn('titular_person2');
            $table->dropColumn('vocal_person');
            $table->dropColumn('owner');
            $table->dropColumn('address_id');
            $table->dropColumn('section_id');
            $table->dropColumn('president');
            $table->dropColumn('secretary');
            $table->dropColumn('teller1');
            $table->dropColumn('teller2');
            $table->dropColumn('substitute1');
            $table->dropColumn('substitute2');
            $table->dropColumn('substitute3');
        });

        Schema::table('fed_districts', function (Blueprint $table) {
            $table->dropColumn('municipality_id');
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
            $table->dropColumn('representative');
            $table->dropColumn('alternate');
        });

        Schema::table('group_person', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->dropColumn('person_id');
        });

        Schema::table('person_box', function (Blueprint $table) {
            $table->dropColumn('person_id');
            $table->dropColumn('box_id');
            $table->dropColumn('section_id');
        });

        Schema::table('political_function_person', function (Blueprint $table) {
            $table->dropColumn('political_function_id');
            $table->dropColumn('person_id');
        });

        Schema::table('role_system_function', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('system_function_id');
        });

        Schema::table('social_network_person', function (Blueprint $table) {
            $table->dropColumn('social_network_id');
            $table->dropColumn('person_id');
        });

        Schema::table('user_role', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('role_id');
            $table->dropColumn('promotor');
        });

        Schema::table('validation_person', function (Blueprint $table) {
            $table->dropColumn('validation_id');
            $table->dropColumn('person_id');
        });

        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn('area_id');
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
        });

        Schema::table('gen_representation', function (Blueprint $table) {
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('person_id');
        });

        Schema::table('fed_entitys', function (Blueprint $table) {
            $table->dropColumn('titular_person');
            $table->dropColumn('vocal_person');
            $table->dropColumn('representative');
            $table->dropColumn('alternate');
        });

        Schema::table('communications', function (Blueprint $table) {
            $table->dropColumn('phone_code_id');
            $table->dropColumn('person_id');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('id_user_to');
            $table->dropColumn('id_user_from');
        });

        Schema::table('postal_codes', function (Blueprint $table) {
            $table->dropColumn('fed_entity_id');
            $table->dropColumn('municipality_id');
            $table->dropColumn('colony_id');
        });

        Schema::table('role_group', function (Blueprint $table) {
            $table->dropColumn('role_id');
            $table->dropColumn('group_id');
        });

        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('default_role');
        });
    }
}
