<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddParentIdToSubOrderExtraAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_order_extra_addons', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_extra_payment_history_id')->nullable();
            $table->foreign('sub_extra_payment_history_id')->references('id')->on('sub_order_extra_addon_payment_histories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_order_extra_addons', function (Blueprint $table) {
            //
        });
    }
}
