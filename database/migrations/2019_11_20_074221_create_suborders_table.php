<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubordersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suborders', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('order_id_pk')->comment('order table in order id unique and pk');
          $table->foreign('order_id_pk')->references('id')->on('orders')->onDelete('cascade');
          $table->unsignedBigInteger('order_number_id')->comment('order table in order_id for multi perpose');
          //$table->foreign('order_number_id')->references('order_number')->on('orders')->onDelete('cascade');
          $table->unsignedBigInteger('user_vehicle_id')->nullable();
          $table->foreign('user_vehicle_id')->references('id')->on('vehicles')->onDelete('cascade');
          $table->unsignedBigInteger('service_id')->nullable();
          $table->foreign('service_id')->references('id')->on('service')->onDelete('cascade');
          $table->unsignedBigInteger('insurance_car_id')->nullable();
          $table->foreign('insurance_car_id')->references('id')->on('insurance_car_details')->onDelete('cascade');
          $table->unsignedBigInteger('addons_id')->nullable();
          $table->foreign('addons_id')->references('id')->on('service_addons')->onDelete('cascade');
          $table->enum('service_type',[1,2])->default('1')->comment('1=service and 2 =for addons');
          $table->float('sub_amount',10,3)->default('0.00');
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
        Schema::dropIfExists('suborders');
    }
}
