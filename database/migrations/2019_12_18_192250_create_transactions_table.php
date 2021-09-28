<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('category_type',['0','1','2','3'])->default(0)->comment('0= None 1=Normal, 2=Insurance , 3=Emergency');
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->unsignedBigInteger('service_provider_id')->nullable();
            $table->foreign('service_provider_id')->references('id')->on('users')->onDelete('cascade');
            $table->double('service_amount',8,2)->default(0);
            $table->double('fixed_amount',8,2)->default(0);
            $table->double('maak_percentage',8,2)->default(0);
            $table->double('commission',8,2)->default(0);
            $table->double('net_payable',8,2)->default(0);
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
        Schema::dropIfExists('transactions');
    }
}
