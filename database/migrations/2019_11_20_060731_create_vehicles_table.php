<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->string('guest_id')->nullable();
          $table->unsignedBigInteger('vehicle_type_id');
          $table->foreign('vehicle_type_id')->references('id')->on('vehicle_type')->onDelete('cascade');
          $table->unsignedBigInteger('brand_id');
          $table->foreign('brand_id')->references('id')->on('vehicle_brand')->onDelete('cascade');
          $table->unsignedBigInteger('model_id');
          $table->foreign('model_id')->references('id')->on('vehicle_model')->onDelete('cascade');
          $table->string('registration_number',100)->nullable();
          $table->string('year_of_manufacture')->nullable();
          $table->string('location')->nullable();
          $table->string('service_date')->nullable();
          $table->string('service_time')->nullable();
          $table->tinyInteger('is_primary')->default('1')->comment('1=yes,0=no');
          $table->tinyInteger('is_active')->default('1')->comment('1=active,0=inactive');
          $table->tinyInteger('is_delete')->default('0')->comment('1=deleted,0=not deleted');
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
        Schema::dropIfExists('vehicles');
    }
}
