<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubOrderExtraAddonPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_order_extra_addon_payment_histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->double('amount',8,3)->default(0);
            $table->enum('payment_type',['0','1','2'])->default(0)->comment('0 : None, 1 : By Cash, 2 : Online Payment');
            $table->enum('payment_status',['0','1','2'])->default(0)->comment('0 : Pending, 1 : Success, 2 : Fail');
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
        Schema::dropIfExists('sub_order_extra_addon_payment_histories');
    }
}
