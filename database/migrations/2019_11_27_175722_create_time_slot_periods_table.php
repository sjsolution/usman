<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeSlotPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_slot_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('time_slot_id');
            $table->foreign('time_slot_id')->references('id')->on('time_slot')->onDelete('cascade');
            $table->string('period')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
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
        Schema::dropIfExists('time_slot_periods');
    }
}
