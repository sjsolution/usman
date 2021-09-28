<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookedTechniciansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_technicians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('service_provider_id')->nullable();
            $table->foreign('service_provider_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('technician_id')->nullable();
            $table->foreign('technician_id')->references('id')->on('users')->onDelete('cascade');
            $table->date('booking_date')->nullable();
            $table->time('booking_time')->nullable();
            $table->enum('status',['0','1','2'])->default(0)->comment('0: None 1:Booked 2:Service Completed');            
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
        Schema::dropIfExists('booked_technicians');
    }
}
