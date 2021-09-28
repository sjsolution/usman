<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_brand', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('vehicle_type_id');
            $table->foreign('vehicle_type_id')->references('id')->on('vehicle_type')->onDelete('cascade');
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
        Schema::dropIfExists('vehicle_brand');
    }
}
