<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserwalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('userwallet', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('sender_id')->nullable()->comment('sender user id');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->longText('description')->nullable();
            $table->decimal('transaction_amount',10,3)->default('0.000');
            $table->decimal('closing_amount',10,3)->default('0.000');
            $table->enum('transaction_type',[0,1,2])->default(0)->comment('0=add money,1=credit,2=debit');
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
        Schema::dropIfExists('userwallet');
    }
}
