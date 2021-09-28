<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsuranceCarDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_car_details', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->unsignedBigInteger('user_vehicle_id');
          $table->string('guest_id')->nullable();
          $table->foreign('user_vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
          $table->unsignedBigInteger('service_id');
          $table->foreign('service_id')->references('id')->on('service')->onDelete('cascade');
          $table->string('insurance_start_date')->nullable();
          $table->string('insurance_type')->nullable()->comment('1=new policy,0=old policy');
          $table->string('mobile_number')->nullable();
          $table->string('civil_id_front')->nullable()->comment('front image');
          $table->string('civil_id_back')->nullable()->comment('back image');
          $table->string('car_registration_number')->nullable()->comment('image');
          $table->integer('current_car_estimation_value')->nullable();
          $table->mediumText('chassis_number')->nullable()->comment('Usually on left side of the front glass');
          $table->mediumText('description')->nullable();
          $table->mediumText('images')->nullable();
          $table->tinyInteger('is_agree')->default('1')->comment('1=agree,0=not agree');
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
        Schema::dropIfExists('insurance_car_details');
    }
}
