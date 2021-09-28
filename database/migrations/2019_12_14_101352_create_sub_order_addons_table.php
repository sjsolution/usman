<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubOrderAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_order_addons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('suborder_id');
            $table->foreign('suborder_id')->references('id')->on('suborders')->onDelete('cascade');
            $table->unsignedBigInteger('service_addon_id');
            $table->foreign('service_addon_id')->references('id')->on('service_addons')->onDelete('cascade');
            $table->double('amount', 8, 3);	
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
        Schema::dropIfExists('sub_order_addons');
    }
}
