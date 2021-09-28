<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpTimeSlotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sp_time_slot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('day_id');
            $table->foreign('day_id')->references('id')->on('days')->onDelete('cascade');
            $table->unsignedBigInteger('time_slot_id');
            $table->foreign('time_slot_id')->references('id')->on('time_slot')->onDelete('cascade');
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('buffer_length')->nullable();
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
        Schema::dropIfExists('sp_time_slot');
    }
}
