<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAddonCancelledToSubOrderExtraAddonPaymentHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_order_extra_addon_payment_histories', function (Blueprint $table) {
            $table->enum('is_addon_canceled',['0','1'])->default(0)->comment("0:No,1:Yes");
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
            $table->dropColumn('is_addon_canceled',['0','1'])->default(0)->comment("0:No,1:Yes");
        });
    }
}
