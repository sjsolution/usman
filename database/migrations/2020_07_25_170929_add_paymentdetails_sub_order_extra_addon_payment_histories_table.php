<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentdetailsSubOrderExtraAddonPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_order_extra_addon_payment_histories', function (Blueprint $table) {
            $table->String('paymentToken')->nullable();
            $table->String('paymentId')->nullable();
            $table->longText('description')->nullable();
            $table->longText('payment_details')->nullable();
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
            $table->dropColumn('description');
            $table->dropColumn('payment_details');
            $table->dropColumn('paymentToken');
            $table->dropColumn('paymentId');
        });
    }
}
