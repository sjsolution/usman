<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOnlinepaidSubOrderExtraAddonPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_order_extra_addon_payment_histories', function (Blueprint $table) {
            $table->double('paid_from_wallet',8,3)->default(0);
            $table->double('paid_from_account',8,3)->default(0);
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
            $table->dropColumn('paid_from_wallet');
            $table->dropColumn('paid_from_account');
        });
    }
}
