<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('service_provider_id')->nullable();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->enum('category_type',['0','1','2','3'])->default(0)->comment('0= None 1=Normal, 2=Insurance , 3=Emergency');
            $table->double('service_amount',8,2)->default(0);
            $table->double('fixed_amount',8,2)->default(0);
            $table->double('maak_percentage',8,2)->default(0);
            $table->double('commission',8,2)->default(0);
            $table->double('net_payable',8,2)->default(0);
            $table->string('payment_token')->nullable();
            $table->double('knet_fees',8,2)->default(0);
            $table->double('others_fees',8,2)->default(0);
            $table->string('payment_id')->nullable();
            $table->enum('status',['0','1','2','3'])->default(0)->comment('0 : Pending 1 : Success 2 : Failure 3 : Cancelled');
            $table->double('user_applicable_fee',10,3)->default(0);

            $table->bigInteger('paymentId')->nullable();
            $table->bigInteger('transactionId')->nullable();
            $table->float('amount')->nullable();
            $table->bigInteger('referenceId')->nullable();
            $table->bigInteger('invoiceReference')->nullable();
            $table->string('invoiceStatus')->nullable();
            $table->bigInteger('invoiceId')->nullabale();
            $table->string('customerName')->nullable();
            $table->string('paymentGateway')->nullable();    
            $table->float('getCustomerServiceCharge')->nullable();
            $table->integer('myInvoiceId')->nullable();
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
        Schema::dropIfExists('payments');
    }
}
