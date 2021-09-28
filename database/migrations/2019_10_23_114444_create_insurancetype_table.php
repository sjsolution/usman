<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsurancetypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurancetype', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name_en')->nullable();
            $table->string('name_ar')->nullable();
            $table->integer('type')->default('0')->comment('not mention');
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
        Schema::dropIfExists('insurancetype');
    }
}
