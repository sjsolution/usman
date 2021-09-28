<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('user_id');
          $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
          $table->bigInteger('order_number')->comment('This order number is used to suborder table for tracking');
          $table->float('total_amount',10,3)->nullable()->default('0.00');
          $table->float('final_amount',10,3)->nullable()->default('0.00');
          $table->float('wallet_amount',10,3)->nullable()->default('0.00');
          $table->float('coupon_amount',8,3)->nullable()->default('0.000');
          $table->float('service_charge',8,3)->nullable()->default('0.000');
          $table->string('coupon_code')->nullable();
          $table->enum('payment_type',[1,2,3])->comment('1 for wallet,2=credit card or debit card,3=wallet and card');
          $table->enum('payment_status',[0,1,2,3])->default(0)->comment('0=pending,1=processing,2=success,3=Failed');
          $table->enum('status',[0,1,2,3,4])->default(0)->comment('0=work status pending,1=work status processing,2=work status done,3=work status Failed,4=work status canceled');
          $table->bigInteger('invoice_id')->nullable();
          $table->bigInteger('transaction_id')->nullable();
          $table->bigInteger('reference_number')->nullable();
          $table->enum('is_deleted',[0,1])->default('0')->comment('1=deleted');
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
        Schema::dropIfExists('orders');
    }
}
