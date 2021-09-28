<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmergencyTimeSlotsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emergency_time_slots', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id')->comment('service provider id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->unsignedBigInteger('day_id');
          $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
          $table->unsignedBigInteger('sp_time_slot_id');
          $table->foreign('sp_time_slot_id')->references('id')->on('sp_time_slot')->onDelete('cascade');
          $table->string('start_time')->nullable();
          $table->string('end_time')->nullable();
          $table->tinyInteger('is_active')->default('1')->comment('1=current emegency time active mode,0 for old time ');
          $table->tinyInteger('status')->default('1')->comment('1 for active and 0 for inactive');
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
        Schema::dropIfExists('emergency_time_slots');
    }
}
