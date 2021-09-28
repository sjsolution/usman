<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTechnicianTimeSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('technician_time_slot', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id')->comment('service provider is');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->unsignedBigInteger('technician_id')->comment('technician id');
          $table->foreign('technician_id')->references('id')->on('users')->onDelete('cascade');
          $table->unsignedBigInteger('day_id');
          $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
          $table->unsignedBigInteger('sp_time_slot_id');
          $table->foreign('sp_time_slot_id')->references('id')->on('sp_time_slot')->onDelete('cascade');
          $table->string('start_time')->nullable();
          $table->string('end_time')->nullable();
          $table->string('break_from')->nullable();
          $table->string('break_to')->nullable();
          $table->tinyInteger('is_active')->default('1');
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
        Schema::dropIfExists('technician_time_slot');
    }
}
