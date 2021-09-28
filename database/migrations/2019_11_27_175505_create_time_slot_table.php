<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('time_slot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('day_id');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->tinyInteger('is_active')->default('1');
            $table->tinyInteger('status')->default('1')->comment('1 for active 0 for inactive');
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
        Schema::dropIfExists('time_slot');
    }
}
