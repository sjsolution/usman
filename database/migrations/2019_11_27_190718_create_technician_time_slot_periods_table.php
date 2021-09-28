<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicianTimeSlotPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technician_time_slot_periods', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('technician_time_slot_id');
          $table->foreign('technician_time_slot_id')->references('id')->on('technician_time_slot')->onDelete('cascade');
          $table->string('period')->nullable();
          $table->string('start_time')->nullable();
          $table->string('end_time')->nullable();
          $table->tinyInteger('is_changed')->default('0');
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
        Schema::dropIfExists('technician_time_slot_periods');
    }
}
