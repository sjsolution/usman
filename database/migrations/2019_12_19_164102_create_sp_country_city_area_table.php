<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpCountryCityAreaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp_country_city_area', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('country_city_id');
          $table->foreign('country_city_id')->references('id')->on('sp_country_city')->onDelete('cascade');
          $table->unsignedBigInteger('area_id');
          $table->foreign('area_id')->references('id')->on('city_areas')->onDelete('cascade');
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
        Schema::dropIfExists('sp_country_city_area');
    }
}
