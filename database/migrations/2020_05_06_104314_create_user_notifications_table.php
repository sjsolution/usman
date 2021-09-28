<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_notifications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->string('title_en')->nullable();
            $table->string('title_ar')->nullable();
            $table->string('body_en')->nullable();
            $table->string('body_ar')->nullable();
            $table->enum('notification_type',['0','1','2','3','101','102','103'])->comment('0: None, 1:Booking 2:Wallet ,3:Broadcast ,101:Add on service online payment reuqest ,102 :Add on service paid by cash ,103:Add On service removed')->default(0); 
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
        Schema::dropIfExists('user_notifications');
    }
}
