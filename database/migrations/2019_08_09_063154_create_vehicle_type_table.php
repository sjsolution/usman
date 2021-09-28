<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVehicleTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicle_type', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->string('name_en',50)->nullable();
          $table->string('name_ar',50)->nullable();
          $table->string('image')->nullable();
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
        Schema::dropIfExists('vehicle_type');
    }
}
