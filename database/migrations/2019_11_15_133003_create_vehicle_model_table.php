<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleModelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_model', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_brand_id');
            $table->foreign('vehicle_brand_id')->references('id')->on('vehicle_brand')->onDelete('cascade');
            $table->string('name_en',50)->nullable();
            $table->string('name_ar',50)->nullable();
            $table->enum('is_active',[0,1])->default(0)->comment('1=inactive');
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
        Schema::dropIfExists('vehicle_model');
    }
}
