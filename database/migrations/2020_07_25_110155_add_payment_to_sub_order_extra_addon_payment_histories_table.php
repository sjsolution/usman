<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentToSubOrderExtraAddonPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_order_extra_addon_payment_histories', function (Blueprint $table) {
            $table->enum('paid_by',['0','1','2','3','4','5'])->default(0)->comment("1 : Wallet Payment , 2 : Knet 3 : knet & wallet, 4: Credit Card, 5: Credit Card & Wallet");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_order_extra_addon_payment_histories', function (Blueprint $table) {
            //
        });
    }
}
